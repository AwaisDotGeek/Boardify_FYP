<template>
    <div class="h-screen flex justify-center items-center gap-5 py-10">

        <!-- Game History -->
        <History
            :moves="moves"
            :player1="player1 && player1"
            :player2="player2 && player2"
        />

        <!-- Game space -->
        <div class="flex flex-col items-center gap-2">
            <PlayerCard
                :user="player2User"
                :currentPlayer="currentPlayer"
                :playerSymbol="player2Symbol"
                :captures="player2_Captures"
            ></PlayerCard>

            <Board
                :boardState="boardState"
                :colorPair="colorPair"
                :loadFor="loadFor"
                @selectPiece="handleSelectPiece"
                :showPromotionOption="shouldShowPromotionOption"
                @promotePawnTo="handlePawnPromotion"
                :playerToMove="playerToMove"
            />

            <PlayerCard
                :user="player1User"
                :currentPlayer="currentPlayer"
                :playerSymbol="player1Symbol"
                :captures="player1_Captures"
            ></PlayerCard>
        </div>

        <!-- Chat space -->
        <Chat :chatId="uniqueUrlCode" title="Chat" :styleClasses="'w-[350px] min-h-[100%] max-h-[100%] my-5 shadow-sm shadow-black-5 rounded-lg overflow-hidden'"></Chat>

        <div v-if="winner" class="absolute z-[10] w-full h-full flex justify-center items-center">
            <Winner :winner="winner" />
        </div>

<!--        <div class="">-->
<!--            <button @click="resetBoard">Reset Board</button>-->
<!--        </div>-->
    </div>
</template>

<script>
import Board from "./components/Board.vue";
import {calculateValidMoves, getImgSrc, isGameOver, isKingInDanger} from "./helpers/functions.js";
import axios from "axios";
import PlayerCard from "./components/PlayerCard.vue";
import History from "./components/History.vue";
import Chat from "../../discussions/Chat.vue";
import Winner from "./components/Winner.vue";

export default {
    data() {
        return {
            playerToMove: "w", // w -> white, b -> black
            currentValidMoves: [],
            winner: null,
            boardState: [
                // Row 1
                [
                    {
                        piece: { name: "bR" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bN" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bB" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bQ" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bK" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bB" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bN" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bR" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 2
                [
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "bP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 3
                [
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 4
                [
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 5
                [
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 6
                [
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: null,
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 7
                [
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wP" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
                // Row 8
                [
                    {
                        piece: { name: "wR" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wN" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wB" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wQ" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wK" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wB" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wN" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                    {
                        piece: { name: "wR" },
                        highlighted: false,
                        highlightedForMove: false,
                    },
                ],
            ],

            // History
            moves: [],

            loadFor: null, // white player or black player
            colorPair: { light: "#F0D9B5", dark: "#B58863" },
            selectedPiece: null,

            uniqueUrlCode: null, // The unique url for this game

            player1: null,
            player2: null,
            currentPlayer: null,
            player1_Captures: [],
            player2_Captures: [],

            isMoveMade: false,
            isClickDisabled: false,
            movedFrom: null,
            movedTo: null,

            isGameStateSavedInDB: false,

            shouldShowPromotionOption: false,

            // We want to keep track of kings coords to later be able to check if they are in danger. These coords are used in helper function
            wk_coords: {row: 7,col: 4},
            bk_coords: {row: 0,col: 4}
        };
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },

        player1User() {
            return this.player1 ? this.player1 : "fetching user details";
        },

        player1Symbol() {
            return (
                (this.player1 && (this.player1.symbol || this.player1.color)) ||
                "fetching color"
            );
        },

        player2User() {
            return this.player2 ? this.player2 : "fetching user details";
        },

        player2Symbol() {
            return (
                (this.player2 && (this.player2.symbol || this.player2.color)) ||
                "fetching color"
            );
        },
    },

    components: {
        Winner,
        Chat,
        History,
        PlayerCard,
        Board,
    },

    created() {
        // Add the Danger attribute to Kings
        this.wk_coords = this.getKingCoords('wK');
        this.bk_coords = this.getKingCoords('bK');
        this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = false;
        this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = false;

        // Is this player1 or player2 ?
        // Get the URL from the browser
        const currentURL = window.location.href;

        // Trim the URL on '/'
        const urlParts = currentURL.split("/");
        const uniqueGameUrl = urlParts[urlParts.length - 1];
        this.uniqueUrlCode = uniqueGameUrl;

        // Call the fetchPlayersSequence function with the unique gameUrl
        this.fetchPlayersSequence(uniqueGameUrl);

        // It is possible that a player accidentally reloads the game
        this.fetchGameState();

        // <----- Game Channel ----->
        const gameChannel = "chess_channel." + uniqueGameUrl;
        Echo.channel(gameChannel).listen("MoveMade", (event) => {

            // Clear Previously Highlighted Squares
            this.clearHighlightedSquaresOnBoard(event.gameData.boardState);

            // Highlight the last move squares (From -> To)
            let movingFromRow = event.gameData.movedFrom.coords.row;
            let movingFromCol = event.gameData.movedFrom.coords.col;
            let movingToRow = event.gameData.movedTo.coords.row;
            let movingToCol = event.gameData.movedTo.coords.col;

            event.gameData.boardState[movingFromRow][
                movingFromCol
            ].highlighted = true;
            event.gameData.boardState[movingToRow][movingToCol].highlighted =
                true;

            // Update board state
            this.boardState = [...event.gameData.boardState];

            this.wk_coords = this.getKingCoords('wK');
            this.bk_coords = this.getKingCoords('bK');

            // console.log(this.wk_coords.row, this.wk_coords.col);
            // console.log("Is white king in danger - broadcast " + this.checkWhiteKingInDanger());


            // Add move to history
            // The move and counter move is completed when black does the move
            // also transfer the path to img of a moved piece
            // If a piece captures a piece, we want to show it like: [piece-img]x[indexer(example-e4)], reads as, piece Takes on e4
            if (event.gameData.player.color === "white") {
                // Check for black king danger
                this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = this.checkBlackKingInDanger();

                const { indexer, imgPath } = this.getMoveAndPieceImg(event);

                this.moves = [
                    ...this.moves,
                    {
                        whiteMove: indexer,
                        whitePieceImg: imgPath,
                    },
                ];
            } else {
                let whiteKingCoords = this.getKingCoords('wK');
                // Check for White king danger
                this.boardState[whiteKingCoords.row][whiteKingCoords.col].highlightedForDanger = this.checkWhiteKingInDanger();

                const { indexer, imgPath } = this.getMoveAndPieceImg(event);

                this.moves[this.moves.length - 1].blackMove = indexer;
                this.moves[this.moves.length - 1].blackPieceImg = imgPath;
            }

            // Update player to move
            this.currentPlayer =
                event.gameData.player.user_id === this.player1.user_id
                    ? this.player2
                    : this.player1;

            this.playerToMove = this.currentPlayer.color[0];

            // Save game state in database
            this.saveGameStateInDB();
        });

        // Initialize Chat
        this.initializeChat(uniqueGameUrl);
    },

    methods: {
        async initializeChat(gameUrl) {
            await axios.post('/initialize-chat', {
                gameUrl: gameUrl
            }).then((response) => {
                // console.log(response);
            })
        },

        async handleSelectPiece(selectedPiece) {
            if (this.isClickDisabled) {
                return;
            }

            selectedPiece = selectedPiece.selectedPiece; // it is coming like => { selectedPiece: {} }
            // console.log("Player to move ", this.playerToMove);

            if (!selectedPiece.square.piece) {
                this.handleEmptySquare(selectedPiece);
            } else {
                this.handleOccupiedSquare(selectedPiece);
            }

            // Check if the piece was moved and post
            if (this.isMoveMade) {
                // Check for pawn promotion
                if (
                    this.currentValidMoves.some((move) => move === "promotion")
                ) {
                    this.shouldShowPromotionOption = true;
                }

                if (!this.shouldShowPromotionOption) {
                    // Post the move
                    await this.postTheMove();

                    // Sort Captures list
                    this.sortCaptures();

                    this.isMoveMade = false;
                }
                else {
                    await new Promise((resolve) => {
                        const intervalId = setInterval(() => {
                            if (!this.shouldShowPromotionOption) {
                                clearInterval(intervalId);
                                resolve();
                            }
                        }, 100);
                    });

                    // Once the user has made their promotion choice, post the move
                    await this.postTheMove();

                    // Sort Captures list
                    this.sortCaptures();

                    this.isMoveMade = false;
                }

                let isBlackKingInDanger = this.checkBlackKingInDanger();
                this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = isBlackKingInDanger;

                let whiteKingInDanger = this.checkWhiteKingInDanger();
                this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = whiteKingInDanger;

                // console.log('Is White King in danger ? After move ' + whiteKingInDanger);

                if (isBlackKingInDanger) {
                    let gameOver = isGameOver(this.boardState, this.bk_coords, 'b');
                    if (gameOver) {
                        let player1 = JSON.parse(JSON.stringify(this.player1));
                        this.winner = (player1.color = 'white' ? this.player1 : this.player2);

                    }
                }

                if (whiteKingInDanger) {
                    let gameOver = isGameOver(this.boardState, this.wk_coords, 'w');
                    if (gameOver) {
                        let player1 = JSON.parse(JSON.stringify(this.player1));
                        this.winner = (player1.color = 'black' ? this.player1 : this.player2);
                    }
                }

                if (this.winner) {
                    // Mark the game finished!
                    this.finishTheGame();
                }
            }
        },

        // Method to check king in danger
        checkBlackKingInDanger() {
            return isKingInDanger(this.boardState, this.bk_coords, 'b');
        },

        checkWhiteKingInDanger() {
            return isKingInDanger(this.boardState, this.wk_coords, 'w');
        },

        // Method to sort captures list
        sortCaptures() {
            // Define the order of piece names
            const pieceOrder = {
                Q: 1, // Queen
                R: 2, // Rook
                B: 3, // Bishop
                N: 4, // Knight
                P: 5, // Pawn
            };

            // Sort player1_Captures
            this.player1_Captures.sort((a, b) => {
                return pieceOrder[a.name] - pieceOrder[b.name];
            });

            // Sort player2_Captures
            this.player2_Captures.sort((a, b) => {
                return pieceOrder[a.name] - pieceOrder[b.name];
            });
        },

        // Method to handle logic when an empty square is selected
        handleEmptySquare(selectedPiece) {
            if (this.selectedPiece) {
                this.movePiece(this.selectedPiece, selectedPiece);
                this.clearShownMoves();
            }
        },

        // Method to handle logic when an occupied square is selected
        handleOccupiedSquare(selectedPiece) {
            if (
                selectedPiece.square.piece.name.startsWith(this.playerToMove) &&
                this.user.user_id === this.currentPlayer.user_id
            ) {
                this.selectPiece(selectedPiece);
            } else {
                this.capturePiece(selectedPiece);
            }
        },

        // Method to select a piece and highlight possible moves
        selectPiece(selectedPiece) {
            if (this.selectedPiece) {
                this.selectedPiece.square.highlighted = false;
            }
            this.selectedPiece = selectedPiece;
            this.selectedPiece.square.highlighted = true;
            this.clearShownMoves();

            if (this.playerToMove === 'b') {
                let isInDanger = this.checkBlackKingInDanger(this.boardState, this.bk_coords, 'b');
                this.currentValidMoves = calculateValidMoves(
                    this.boardState,
                    this.selectedPiece,
                    isInDanger,
                    this.bk_coords,
                );
            } else if (this.playerToMove === 'w') {
                let isInDanger = this.checkWhiteKingInDanger(this.boardState, this.wk_coords, 'w');
                this.currentValidMoves = calculateValidMoves(
                    this.boardState,
                    this.selectedPiece,
                    isInDanger,
                    this.wk_coords,
                );
            }

            this.highlightMovesOnBoard();
        },

        // Method to capture a piece
        capturePiece(selectedPiece) {
            if (this.selectedPiece) {
                const movedFrom = this.selectedPiece;
                this.movePiece(movedFrom, selectedPiece);
                this.clearShownMoves();
            }
        },

        // Method to move a piece
        movePiece(movedFrom, movedTo) {
            if (this.isValidMove(movedTo.coords)) {
                this.movedFrom = {
                    ...movedFrom,
                    piece: { ...movedFrom.square.piece },
                };
                this.movedTo = {
                    ...movedTo,
                    piece: { ...movedTo.square.piece },
                };

                movedTo.square.piece = movedFrom.square.piece;
                movedFrom.square.piece = null;
                this.selectedPiece.square.highlighted = false;
                this.selectedPiece = null;
                this.isMoveMade = true;
                this.isGameStateSavedInDB = false;

                if (movedTo.square.piece.name === 'bK') {
                    this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = false;
                    this.bk_coords = this.getKingCoords('bK');
                }
                else if (movedTo.square.piece.name === 'wK') {
                    this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = false;
                    this.wk_coords = this.getKingCoords('wK');
                }

            } else {
                this.selectedPiece.square.highlighted = false;
            }
        },

        // Implement the move validation logic based on your chess rules
        isValidMove(toCoords) {
            // Example: Check if the piece can legally move from 'fromSquare' to 'toSquare'
            // You need to implement this based on the type of chess piece and game rules
            return this.currentValidMoves.some((move) => {
                return move.row === toCoords.row && move.col === toCoords.col;
            });
        },

        clearHighlightedSquaresOnBoard(boardState) {
            boardState.map((row) => {
                row.map((square) => {
                    square.highlighted = false;
                });
            });
        },

        // Get move and pieceImg
        // Helper function for creating moves info somewhere at lines 460-480
        getMoveAndPieceImg(event) {
            // Indexer looks like: e5, d4, etc representing a single move
            let indexer =
                event.gameData.movedTo.alphaIndexer +
                event.gameData.movedTo.numericIndexer;
            let imgPath = event.gameData.movedTo.square.piece.imgSrc;

            // was it a capture ?
            let capture = false;
            if (
                event.gameData.movedTo.piece &&
                event.gameData.movedTo.piece.name
            ) {
                capture = true;
                indexer = "x" + indexer;
            }

            // Update captures array
            if (capture) {
                if (event.gameData.player.color === "white") {
                    if (this.player1.color === "white") {
                        this.player1_Captures.push({
                            name: event.gameData.movedTo.piece.name[1], // we only need last character of name for sorting
                            imgSrc: event.gameData.movedTo.piece.imgSrc,
                        });
                    } else {
                        this.player2_Captures.push({
                            name: event.gameData.movedTo.piece.name[1], // we only need last character of name for sorting
                            imgSrc: event.gameData.movedTo.piece.imgSrc,
                        });
                    }
                } else {
                    if (this.player1.color === "black") {
                        this.player1_Captures.push({
                            name: event.gameData.movedTo.piece.name[1], // we only need last character of name for sorting
                            imgSrc: event.gameData.movedTo.piece.imgSrc,
                        });
                    } else {
                        this.player2_Captures.push({
                            name: event.gameData.movedTo.piece.name[1], // we only need last character of name for sorting
                            imgSrc: event.gameData.movedTo.piece.imgSrc,
                        });
                    }
                }
            }

            return { indexer, imgPath };
        },

        clearShownMoves() {
            try {
                if (this.currentValidMoves) {
                    for (let i = 0; i < this.currentValidMoves.length; i++) {
                        this.boardState[this.currentValidMoves[i].row][
                            this.currentValidMoves[i].col
                        ].highlightedForMove = false;
                    }
                }
            } catch (error) {}
        },

        highlightMovesOnBoard() {
            // highlight possible squares for move
            try {
                for (let i = 0; i < this.currentValidMoves.length; i++) {
                    this.boardState[this.currentValidMoves[i].row][
                        this.currentValidMoves[i].col
                    ].highlightedForMove = true;
                }
            } catch (error) {}
        },

        //     ================
        async fetchPlayersSequence(uniqueGameUrl) {
            const response = await axios.post("/chess/get_players_sequence", {
                game_url: uniqueGameUrl,
            });

            if (response) {
                if (this.user.user_id === response.data.player1.user_id) {
                    this.player1 = response.data.player1;
                    this.player2 = response.data.player2;
                    this.loadFor = "white";
                } else {
                    this.player1 = response.data.player2;
                    this.player2 = response.data.player1;
                    this.loadFor = "black";
                }

                // Update the player to move
                this.currentPlayer = response.data.player1;
                this.playerToMove = this.currentPlayer.color[0];
            }
        },

        async postTheMove() {
            this.isClickDisabled = true;
            await axios
                .post("/chess/make-move", {
                    gameUrl: this.uniqueUrlCode,
                    currentPlayer: this.currentPlayer,
                    boardState: this.boardState,
                    movedFrom: this.movedFrom,
                    movedTo: this.movedTo,
                })
                .then(() => {
                    this.isMoveMade = false;
                    this.isClickDisabled = false;
                    this.movedFrom.square.piece = null;
                    this.movedFrom = null;
                    this.movedTo = false;
                });
        },

        async saveGameStateInDB() {
            if (!this.isGameStateSavedInDB) {
                await axios
                    .post("/chess/game/save-state", {
                        gameUrl: this.uniqueUrlCode,
                        boardState: this.boardState,
                        moves: this.moves,
                        player1Captures: this.player1_Captures,
                        player2Captures: this.player2_Captures,
                    })
                    .then(() => {
                        this.isGameStateSavedInDB = true;
                    });
            }
        },

        async fetchGameState() {
            const response = await axios.post("/chess/game/get-state", {
                gameUrl: this.uniqueUrlCode,
            });

            if (response.data.status) {
                let moves = JSON.parse(response.data.game.history);
                this.moves = moves;

                // console.log(response.data);

                this.boardState = JSON.parse(response.data.game.board_state);

                // Who did the last move ?
                let lastMove = moves[moves.length - 1];
                // If last move has an instance of blackMove, it means, white and black both had there turns
                if (lastMove.blackMove) {
                    // so, white will now move
                    // Also assign the captures details
                    this.currentPlayer =
                        this.player1 && this.player1.color === "white"
                            ? this.player1
                            : this.player2;
                    this.playerToMove = "w";
                } else {
                    // Black will do the move
                    this.currentPlayer =
                        this.player1 && this.player1.color === "white"
                            ? this.player2
                            : this.player1;
                    this.playerToMove = "b";
                }

                // Captures Details
                let player1_Captures = [];
                let player2_Captures = [];

                if (response.data.game.player1_captures.length) {
                    player1_Captures = JSON.parse(
                        response.data.game.player1_captures,
                    );
                }
                if (response.data.game.player2_captures.length) {
                    player2_Captures = JSON.parse(
                        response.data.game.player2_captures,
                    );
                }

                if (this.player1.color === "white") {
                    this.player1_Captures = player1_Captures;
                    this.player2_Captures = player2_Captures;
                } else {
                    this.player1_Captures = player2_Captures;
                    this.player2_Captures = player1_Captures;
                }

                this.sortCaptures();
            }

            // Kings danger
            this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = this.checkBlackKingInDanger();
            this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = this.checkWhiteKingInDanger();

        },

        async resetBoard() {
            await axios.post("/chess/reset", {
                gameUrl: this.uniqueUrlCode,
            });
        },

        handlePawnPromotion(args) {
            let imgSrc = "";
            let playerColor = this.movedTo.square.piece.name[0];
            let name = playerColor;
            switch (args.selected) {
                case "Queen":
                    name = playerColor + "Q";
                    imgSrc = getImgSrc(name);
                    break;
                case "Rook":
                    name = playerColor + "R";
                    imgSrc = getImgSrc(name);
                    break;
                case "Bishop":
                    name = playerColor + "B";
                    imgSrc = getImgSrc(name);
                    break;
                case "Knight":
                    name = playerColor + "N";
                    imgSrc = getImgSrc(name);
                    break;
            }
            this.movedTo.square.piece = {
                name: name,
                imgSrc: imgSrc,
                imgAlt: name,
            };
            this.shouldShowPromotionOption = false;
        },

        // King type = wK/bK
        getKingCoords(kingType){
            // Iterate through all squares on the board
            for (let row = 0; row < 8; row++) {
                for (let col = 0; col < 8; col++) {
                    const square = this.boardState[row][col];
                    // Check if the square contains the black king
                    if (square.piece && square.piece.name === kingType) {
                        return { row, col }; // Return the coordinates of the black king
                    }
                }
            }
            // If black king not found, return null or handle the case as needed
            return null;
        },

        async finishTheGame() {
            await axios.post('/finish-chess-game', { gameUrl: this.uniqueUrlCode, winner: this.winner});
        }
    },
};
</script>
