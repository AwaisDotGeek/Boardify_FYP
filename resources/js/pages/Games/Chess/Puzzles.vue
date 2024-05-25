<template>
    <div class="w-screen h-screen flex justify-center items-center gap-5">
        <!-- Information about puzzles -->
        <div class="hidden lg:block">
            <SideMessage>
                <slot>
                    These puzzles are from real games
                    played by real people like you.
                </slot>
            </SideMessage>

            <SideMessage>
                <slot>
                    Use the question icon in front
                    of your name to get a hint.
                </slot>
            </SideMessage>
        </div>

        <!-- Board -->
        <div class="relative h-fit w-fit flex-col justify-center items-center">
            <div class="mb-3">
                <PlayerCard
                    :user="{ username: 'Bot' }"
                    :currentPlayer="playerToMove"
                    :playerSymbol="loadBoardFor === 'white' ? 'black':'white'"
                    :captures="[]"
                    :score="botScore"
                ></PlayerCard>
            </div>
            <Board
                :boardState="boardState"
                :colorPair="colorPair"
                :loadFor="loadBoardFor"
                :playerToMove="playerToMove"
                @selectPiece="handleSelectPiece"
            />
            <div class="mt-3 flex">
                <PlayerCard
                    :user="user"
                    :currentPlayer="playerToMove"
                    :playerSymbol="loadBoardFor"
                    :captures="[]"
                    :score="playerScore"
                ></PlayerCard>

                <div
                    @click="showHint"
                    class="text-xl font-bold text-gray-300 shadow-xl shadow-black-4 bg-gradient-to-t from-black-4 to-black-1 hover:shadow-black cursor-pointer w-[50px] max-h-[50px] flex justify-center items-center rounded-full">
                    ?
                </div>
            </div>
            <div
                v-if="isHintVisible"
                class="absolute z-10 w-full h-full top-0 flex justify-center items-center">
                <div class="text-center text-lg text-white min-w-[45%] rounded-xl bg-gradient-to-tl from-black-5 to-black-1 px-4 py-8">
                    {{ hint }}
                </div>
            </div>
        </div>

        <!-- Information about puzzles -->
        <div class="hidden lg:block">
            <SideMessage>
                <slot>
                    Moves are pre-defined, you
                    have to figure out the correct ones.
                </slot>
            </SideMessage>

            <SideMessage>
                <slot>
                    You can Solve at most 10 puzzles each day.
                </slot>
            </SideMessage>
        </div>
    </div>
</template>
<script>
import Board from "./components/Board.vue";
import axios from "axios";
import PlayerCard from "./components/PlayerCard.vue";
import { calculateValidMoves, isKingInDanger } from "./helpers/functions.js";
import SideMessage from "./components/SideMessage.vue";
export default {
    components: {
        SideMessage,
        PlayerCard,
        Board,
    },

    data() {
        return {
            puzzle: null,
            fen: '',
            // Puzzle FEN parts
            boardPosition: '',
            playerToMove: '',
            castlingRightsWhite: false,
            castlingRightsBlack: false,
            halfMoveClock: '',
            fullMoveNumber: '',


            boardState: [],
            colorPair: { light: "#F0D9B5", dark: "#B58863" },
            loadBoardFor: '',

            botScore: 0,
            playerScore: 0,

            // Audios / Sounds
            moveSound: new Audio("./assets/moveSound.mp3"),
            captureSound: new Audio("./assets/capture.mp3"),
            wrongMoveSound: new Audio('./assets/notify.mp3'),
            checkSound: new Audio('./assets/move-check.mp3'),


            moves: null, // Received with puzzle
            movesDone: [],
            isInDanger: false, // for king

            // Player piece selection and piece movement variables
            selectedPiece: null,
            currentValidMoves: null,
            goFrom: null, // like f5, g4
            goTo: null, // looks same as go from
            bk_coords: null, // Black King coords
            wk_coords: null, // White King coords
            hint: '',
            isHintVisible: false,

            // last move is stored
            // when next move occurs, this is de-highlighted
            lastMove: null,
        }
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    created() {
        if (!this.puzzle) {
            this.getRandomPuzzle();
        }
    },

    watch: {
        playerToMove() {
            if (
                this.loadBoardFor === 'white' && this.playerToMove === 'b' ||
                this.loadBoardFor === 'black' && this.playerToMove === 'w'
            ) {
                setTimeout(() => {
                    // If not bot moves left, return
                    let botMove = this.moves[0];

                    let fromRow = this.getRowIndex(botMove[1]);
                    let fromCol = this.getColIndex(botMove[0])
                    if (!botMove || this.boardState[fromRow][fromCol].piece.name.startsWith(this.loadBoardFor[0])) {
                        return;
                    }

                    // Do bot move
                    this.doTheMove(botMove);
                }, 1000);
            }
        },

        puzzle(puzzle) {
            this.botScore = 0;
            this.playerScore = 0;
            this.fen = puzzle.FEN;
            let [
                boardPosition, playerToMove, castlingRights, enPassantSquare, halfMoveClock, fullMoveNumber,
            ] = this.fen.split(' ');

            this.loadBoardFor = playerToMove === 'w' ? 'black':'white';

            this.moves = puzzle.Moves.split(' ');

            // Convert FEN to my understandable board
            this.convertBoardPositionToBoardState(boardPosition);

            // Set both Kings coords
            this.setKingsCoords();

            // Show message for user to know his color
            let playAs = playerToMove === 'w' ? 'BLACK' : 'WHITE';
            this.hint = "Play as " + playAs;
            this.isHintVisible = true;

            setTimeout(() => {
                this.hint = '';
                this.isHintVisible = false;

                // Do the bot move
                let botMove = this.moves[0];

                // Update playerToMove
                this.playerToMove = playerToMove;

                this.doTheMove(botMove);
            }, 2000);
        },

        botScore() {
            if (this.botScore > this.playerScore) {
                this.botScore -= this.playerScore;
            }
        },

        playerScore() {
            if (this.playerScore > this.botScore) {
                this.playerScore -= this.botScore;
            }
        },
    },

    methods: {
        async getRandomPuzzle() {
            await axios.get('/get-random-chess-puzzle')
                .then((response) => {
                    this.puzzle = response.data.puzzle;
                })
                .catch((error) => {
                    console.log("An error occurred while fetching puzzle");
                    console.log(error);
                });
        },

        async startNewPuzzle() {
            this.hint = "Puzzle finished. Loading a new one!"
            this.isHintVisible = true;
            this.movesDone = [];

            await axios.post('/update-puzzles-solved');

            setTimeout(() => {
                this.hint = '';
                this.isHintVisible = false;
                this.boardState = [];
                this.getRandomPuzzle();
            }, 2000);
        },

        setKingsCoords() {
            for (let i = 0; i < 8; i++) {
                for (let j = 0; j < 8; j++) {
                    const square = this.boardState[i][j];
                    if (square.piece && square.piece.name.endsWith('K')) {
                        if (square.piece.name.startsWith('w')) { // white king
                             this.wk_coords = { row: i, col: j };
                        } else { // black king
                            this.bk_coords = { row: i, col: j };
                        }
                    }
                }
            }
        },

        // Takes FEN board-state and returns board state 'THE MY WAY'
        // Convert FEN to boardState
        // 1R6/8/2P2pk1/1K6/7p/1P6/8/2r5
        convertBoardPositionToBoardState(boardPosition) {
            let rowStates = boardPosition.split('/');

            rowStates.map((rowState,_) => {
                let boardRow = [];
                for (let i = 0; i < rowState.length; i++) {
                    let char = rowState[i];

                    // If it is a piece
                    if (char.charCodeAt(0) >= 65 && char.charCodeAt(0) <= 92 || char.charCodeAt(0) >= 97 && char.charCodeAt(0) <= 122) {
                        let pieceName = this.convertPieceNotation(char);
                        boardRow.push(
                            {
                                piece: { name: pieceName },
                                highlighted: false,
                                highlightedForMove: false,
                            }
                        )
                    }
                    // If it is an empty Square
                    else {
                        let emptySquares = parseInt(char);
                        for(let i = 1; i <= emptySquares; i++) {
                            boardRow.push(
                                {
                                    piece: null,
                                    highlighted: false,
                                    highlightedForMove: false,
                                }
                            )
                        }
                    }
                }

                this.boardState.push(boardRow);
            });
        },

        // Takes a FEN piece notation and returns piece name 'THE MY WAY'
        convertPieceNotation(piece) {
            const pieceMap = {
                'k': 'bK',
                'q': 'bQ',
                'r': 'bR',
                'b': 'bB',
                'n': 'bN',
                'p': 'bP',
                'K': 'wK',
                'Q': 'wQ',
                'R': 'wR',
                'B': 'wB',
                'N': 'wN',
                'P': 'wP'
            };

            return pieceMap[piece] || piece;
        },

        // Move samples => d7h3 e2f4 h3f5 d1d2
        doTheMove(move) {
            if (this.moves[0] !== move) {
                this.wrongMoveSound.play();
                return;
            }

            // Remove the move from moves
            this.movesDone.push(this.moves.shift());

            // De-highlight last move
            if(this.lastMove) {
                this.deHighlightLastMove();
            }

            // Calculate the source and target location
            let fromRow = this.getRowIndex(parseInt(move[1]));
            let fromCol = this.getColIndex(move[0]);
            let toRow = this.getRowIndex(parseInt(move[3]))
            let toCol = this.getColIndex(move[2]);

            // Calculate Score
            if (
                this.playerToMove === 'w' && this.loadBoardFor === 'white' ||
                this.playerToMove === 'b' && this.loadBoardFor === 'black'
            ) { // real player
                this.calculateScoreForPlayer(this.boardState[toRow][toCol].piece);
            } else {
                this.calculateScoreForBot(this.boardState[toRow][toCol].piece);
            }

            // If king is being moved, update king coords
            if (this.boardState[fromRow][fromCol].piece.name.endsWith('K')) {
                if (this.boardState[fromRow][fromCol].piece.name.startsWith('w')){ // white king
                    this.wk_coords = { row: toRow, col: toCol }
                } else { // black king
                    this.bk_coords = { row: toRow, col: toCol }
                }
                // clear the danger when king is moved
                this.boardState[fromRow][fromCol].highlightedForDanger = false;
            }

            // Play sound for move
            // sound code is written before moving as we want to know if target location is empty or not
            if (this.boardState[toRow][toCol].piece) {
                this.captureSound.play();
            } else {
                this.moveSound.play();
            }

            // Move piece
            this.boardState[toRow][toCol].piece = this.boardState[fromRow][fromCol].piece;
            this.boardState[fromRow][fromCol].piece = null;

            // Highlight move done
            this.boardState[fromRow][fromCol].highlighted = true;
            this.boardState[toRow][toCol].highlighted = true;

            // Check for Pawn promotion
            // if move is like, h7h8q, h7h8r etc.
            if (
                move.toLowerCase().endsWith('q') ||
                move.toLowerCase().endsWith('r') ||
                move.toLowerCase().endsWith('b') ||
                move.toLowerCase().endsWith('n')
            ) {
                this.promotePawnTo(toRow, toCol, move[4].toLowerCase());
            }

            // Check if the move put opponents king in danger
            let isInDanger = isKingInDanger(
                this.boardState,
                this.playerToMove === 'w' ? this.bk_coords : this.wk_coords,
                this.playerToMove === 'w' ? 'b' : 'w'
            );

            // De-highlight king danger from previous move
            if (this.isInDanger) {
                this.isInDanger = false;

                this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = false;
                this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = false;
            }

            // Highlight king in danger
            if (isInDanger) {
                   if (this.playerToMove === 'w') { // then black is in danger
                       this.boardState[this.bk_coords.row][this.bk_coords.col].highlightedForDanger = true;
                   } else { // white is in danger
                       this.boardState[this.wk_coords.row][this.wk_coords.col].highlightedForDanger = true;
                   }

                   this.isInDanger = true;
                   this.checkSound.play();
            }

            // Storing, so it is cleared from the board next time
            this.lastMove = move;

            // Update player to move
            this.togglePlayerToMove();

            // Check if moves are finished, then start a new puzzle
            if (this.moves.length === 0) {
                this.startNewPuzzle();
            }

        },

        // Calculate score for real player
        calculateScoreForPlayer(piece) {
            if (piece && piece.name) {
                this.playerScore += this.getScore(piece.name[1].toLowerCase());
            }
        },

        // Calculate score for Bot
        calculateScoreForBot(piece) {
            if (piece && piece.name) {
                this.botScore += this.getScore(piece.name[1].toLowerCase());
            }
        },

        getScore(name) {
            switch (name) {
                case 'q':
                    return 9;
                case 'r':
                    return 5;
                case 'b':
                    return 3;
                case 'n':
                    return 3;
                case 'p':
                    return 1;
            }
        },

        promotePawnTo(pR, pC, name) { // pawn row + pawn col + (q, r, b or n)
            let color = this.playerToMove;
            switch (name) {
                case 'q':
                    this.boardState[pR][pC].piece.name = color + 'Q';
                    break;
                case 'r':
                    this.boardState[pR][pC].piece.name = color + 'R';
                    break;
                case 'b':
                    this.boardState[pR][pC].piece.name = color + 'B';
                    break;
                case 'n':
                    this.boardState[pR][pC].piece.name = color + 'N';
                    break;
            }
        },

        // This function returns integer against column indexer
        // 0 for a, 1 for b, etc
        getColIndex(colIndexer) {
            const colMap = {
                'a': 0,
                'b': 1,
                'c': 2,
                'd': 3,
                'e': 4,
                'f': 5,
                'g': 6,
                'h': 7,
            }

            return colMap[colIndexer] || 0;
        },

        // This function returns integer against row indexer
        // 0 for 8, 1 for 7, and 7 for 1 etc
        getRowIndex(rowIndexer) {
            const rowMap = {
                1: 7,
                2: 6,
                3: 5,
                4: 4,
                5: 3,
                6: 2,
                7: 1,
                8: 0,
            }

            return rowMap[rowIndexer] || 0;
        },

        // We are showing last move on board, when last move is updated, the previous one is de-highlighted
        deHighlightLastMove() {
            let fromRow = this.getRowIndex(parseInt(this.lastMove[1]));
            let fromCol = this.getColIndex(this.lastMove[0]);
            let toRow = this.getRowIndex(parseInt(this.lastMove[3]))
            let toCol = this.getColIndex(this.lastMove[2]);

            this.boardState[fromRow][fromCol].highlighted = false;
            this.boardState[toRow][toCol].highlighted = false;
        },

        // <==========================================>
        handleSelectPiece(selectedPiece) {
            selectedPiece = selectedPiece.selectedPiece;

            if (this.selectedPiece) {
                if (!selectedPiece.square.piece) {
                    this.handleEmptySquare(selectedPiece);
                } else {
                    this.handleSquareWithPiece(selectedPiece);
                }
            } else {
                if (selectedPiece.square.piece && selectedPiece.square.piece.name.startsWith(this.playerToMove)) {
                    this.handleSquareWithPiece(selectedPiece);
                } else {
                    this.wrongMoveSound.play();
                }
            }
        },

        handleEmptySquare(selectedPiece) {
            if (this.selectedPiece && this.currentValidMoves) {
                const isValidMove = this.currentValidMoves.some(move => {
                    return move.row === this.getRowIndex(this.moves[0][3]) &&
                        move.col === this.getColIndex(this.moves[0][2]);
                });

                if (isValidMove) {
                    this.goTo = selectedPiece.alphaIndexer + selectedPiece.numericIndexer;
                    // Check pawn promotion
                    let promotion = '';
                    if (
                        this.moves[0][4] &&
                        this.moves[0][4] === 'q' ||
                        this.moves[0][4] === 'r' ||
                        this.moves[0][4] === 'b' ||
                        this.moves[0][4] === 'n'
                    ) {
                        promotion = this.moves[0][4];
                    }
                    this.doTheMove(this.goFrom + this.goTo + promotion);
                } else {
                    this.wrongMoveSound.play();
                }

                this.clearSelection();
            }
        },

        handleSquareWithPiece(selectedPiece) {
            if (selectedPiece.square.piece.name.startsWith(this.playerToMove)) {
                this.selectPiece(selectedPiece);
                this.highlightValidMoves(selectedPiece);
            } else {
                const isValidCapture = this.isValidCapture(selectedPiece);

                if (isValidCapture) {
                    this.capturePiece(selectedPiece);
                } else {
                    this.wrongMoveSound.play();
                }

                this.clearSelection();
            }
        },

        isValidCapture() {
            return this.currentValidMoves.some(move => {
                return move.row === this.getRowIndex(this.moves[0][3]) &&
                    move.col === this.getColIndex(this.moves[0][2]);
            });
        },

        selectPiece(selectedPiece) {
            this.selectedPiece = selectedPiece;
            this.goFrom = selectedPiece.alphaIndexer + selectedPiece.numericIndexer;
        },

        highlightValidMoves() {
            this.deHighlightMoves(); // De-Highlight old ones
            this.currentValidMoves = calculateValidMoves(
                this.boardState, this.selectedPiece,
                false,
                this.playerToMove === 'w' ? this.wk_coords : this.bk_coords
            );

            if (
                this.currentValidMoves.some((move) => {
                    return move === "promotion"
                })
            ) {
                this.currentValidMoves.pop();
            }

            this.highlightMoves();
        },

        capturePiece(selectedPiece) {
            this.goTo = selectedPiece.alphaIndexer + selectedPiece.numericIndexer;
            this.doTheMove(this.goFrom + this.goTo);
        },

        togglePlayerToMove() {
            this.playerToMove = this.playerToMove === 'w' ? 'b' : 'w';
        },

        clearSelection() {
            this.deHighlightMoves();
            this.selectedPiece = null;
            this.goFrom = null;
            this.goTo = null;
            this.currentValidMoves = null;
        },
        // <==========================================>

        // Helps in updating the board by removing previously shown possible moves
        deHighlightMoves() {
            if (this.currentValidMoves) {
                this.currentValidMoves.forEach((move) => {
                    this.boardState[move.row][move.col].highlightedForMove = false;
                });
            }
        },

        highlightMoves() {
            if (this.currentValidMoves) {
                this.currentValidMoves.forEach((move) => {
                    this.boardState[move.row][move.col].highlightedForMove = true;
                });
            }
        },

        // Show Hint
        showHint() {
            let move = this.moves[0];
            if (!move) {
                this.hint = "This puzzle is Over!";
                return;
            }
            let fromRow = this.getRowIndex(move[1]);
            let fromCol = this.getColIndex(move[0]);
            let toRow = this.getRowIndex(move[3]);
            let toCol = this.getColIndex(move[2]);

            let pieceToMove = this.boardState[fromRow][fromCol].piece.name;
            pieceToMove = this.getCompletePieceName(pieceToMove[1].toLowerCase());

            // Maybe the target location has a piece, or it is empty
            let targetLocation = this.boardState[toRow][toCol].piece;
            if (targetLocation && targetLocation.name) {
                targetLocation = this.getCompletePieceName(targetLocation.name[1].toLowerCase());
            } else {
                targetLocation = move[2] + move[3];
            }

            // this.hint = pieceToMove + (targetLocation.length > 2 ? ' takes ':' to ') + targetLocation;
            this.hint = pieceToMove + (targetLocation.length > 2 ? ' takes ':' moves ');

            this.isHintVisible = true;

            setTimeout(() => {
                this.isHintVisible = false;
            }, 1500);
        },

        getCompletePieceName(pieceName) { // send only the last part, i.e; p, from wp, etc
            const pieceNameMap = {
                'p': 'Pawn',
                'r': 'Rook',
                'b': 'Bishop',
                'n': 'Knight',
                'q': 'Queen',
                'k': 'King',
            }

            return pieceNameMap[pieceName] || null;
        }


    }
}

</script>
