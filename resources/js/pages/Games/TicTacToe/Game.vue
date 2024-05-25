<template>
    <main class="w-screen h-screen flex justify-center items-center gap-5 p-5">
        <div
            id="game"
            class="w-full h-full max-w-3xl flex items-center gap-2 shadow-md shadow-black-4 bg-gradient-to-tl from-black-2 via-black-1 to-black-4 rounded-xl"
        >
            <div
                class="h-full flex flex-col justify-between items-center gap-2 w-full p-2 rounded-lg"
            >
                <PlayerCard
                    :user="player2 ? player2 : 'fetching name'"
                    :isBorderOnTop="false"
                    :currentPlayer="currentPlayer"
                    :playerSymbol="player2 ? player2.symbol : 'fetching symbol'"
                ></PlayerCard>

                <!--                <GameStatus :winner="winner" />-->

                <Board
                    :cells="cells"
                    :winner="winner"
                    @cell-clicked="handleCellClick"
                />

                <PlayerCard
                    :user="player1 ? player1 : 'fetching name'"
                    :isBorderOnTop="true"
                    :currentPlayer="currentPlayer"
                    :playerSymbol="player1 ? player1.symbol : 'fetching symbol'"
                ></PlayerCard>
            </div>
            <!-- <History class="w-1/2" :moves="moves" /> -->
        </div>

        <!-- Chat space -->
        <Chat :chatId="uniqueUrlCode" title="Chat" :styleClasses="'w-[350px] min-h-[100%] max-h-[100%] my-5 shadow-sm shadow-black-5 rounded-lg overflow-hidden'"></Chat>
    </main>
</template>

<script>
import axios from "axios";
import Board from "./components/Board.vue";
import GameStatus from "./components/GameStatus.vue";
import RestartButton from "./components/RestartButton.vue";
import History from "./components/History.vue";
import PlayerCard from "./components/PlayerCard.vue";
import Chat from "../../discussions/Chat.vue";

export default {
    components: {
        Chat,
        Board,
        GameStatus,
        RestartButton,
        History,
        PlayerCard,
    },

    data() {
        return {
            cells: Array(9).fill(null),
            winner: null,
            moves: [], // Initialize with your game moves
            gameFinished: false,

            currentPlayer: null,
            player1: null,
            player2: null,
            uniqueUrlCode: null,
            isMoved: false,
        };
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    created() {
        // Is this player1 or player2 ?
        // Get the URL from the browser
        const currentURL = window.location.href;

        // Trim the URL on '/'
        const urlParts = currentURL.split("/");
        const uniqueGameUrl = urlParts[urlParts.length - 1];
        this.uniqueUrlCode = uniqueGameUrl;

        // Call the fetchPlayersSequence function with the unique gameUrl
        this.fetchPlayersSequence(uniqueGameUrl);

        const gameChannel = "tictactoe_channel." + uniqueGameUrl;
        Echo.channel(gameChannel).listen("MoveMade", (event) => {
            console.log(event);

            // Update the board state
            this.cells = [...event.gameData.board];

            // Check for game over
            if (event.gameData.gameStatus.isOver) {
                this.winner =
                    event.gameData.symbol === this.player1.symbol
                        ? this.player1
                        : this.player2;

                this.restartGame(uniqueGameUrl);
                return;
            }

            // Check if board was full and results in no winner
            if (event.gameData.gameStatus.isFull) {
                this.restartGame(uniqueGameUrl);
            }

            // Update the history
            this.moves = event.gameData.moves;

            // Update the next user to play the move
            this.currentPlayer =
                event.gameData.player.user_id === this.player1.user_id
                    ? this.player2
                    : this.player1;
        });

        // Initialize Chat
        this.initializeChat(uniqueGameUrl);
    },

    methods: {
        async fetchPlayersSequence(uniqueGameUrl) {
            const response = await axios.post("/get_players_sequence", {
                game_url: uniqueGameUrl,
            });

            if (response) {
                if (this.user.user_id === response.data.player1.user_id) {
                    this.player1 = response.data.player1;
                    this.player2 = response.data.player2;
                } else {
                    this.player1 = response.data.player2;
                    this.player2 = response.data.player1;
                }

                // Update the player to move
                this.currentPlayer = response.data.player1;

                // Check for game history in database and update board
                if (response.data.movesHistory !== null) {
                    let moves = response.data.movesHistory;
                    let lastMoveSymbol = "O";
                    for (let i = 0; i < moves.length; i++) {
                        // Split the move into symbol and index
                        let [symbol, index] = moves[i].split("->");

                        index = parseInt(index);

                        // Update the cells array with the symbol at the specified index
                        this.cells[index] = symbol;

                        //
                        lastMoveSymbol = symbol;
                    }

                    // Update the player to move
                    this.currentPlayer =
                        this.player1.symbol === lastMoveSymbol
                            ? this.player2
                            : this.player1;

                    this.moves = [...moves];
                }

                // check if game was finished
                this.gameFinished = response.data.isFinished;
                if (this.gameFinished) {
                    await this.restartGame(uniqueGameUrl);
                }
            }
        },

        async postTheMove(index) {
            await axios
                .post("/tictactoe/make_move", {
                    gameUrl: this.uniqueUrlCode,
                    currentPlayer: this.currentPlayer,
                    index: index,
                    cells: this.cells,
                    moves: this.moves,
                })
                .then(() => {
                    this.isMoved = false;
                });
        },

        handleCellClick(index) {
            // Add logic to handle cell clicks
            if (this.currentPlayer !== this.player1 || this.gameFinished) {
                // this is always player 1,
                // if this is not current player also
                console.log(
                    this.gameFinished
                        ? "This Game was finished!"
                        : "Not your turn!",
                );
                return;
            }

            if (!this.cells[index] && !this.winner && !this.isMoved) {
                this.isMoved = true;
                switch (this.currentPlayer) {
                    case this.player1:
                        this.cells[index] = this.player1.symbol;
                        break;
                    case this.player2:
                        this.cells[index] = this.player2.symbol;
                        break;
                }
                this.moves.push(this.currentPlayer.symbol + " on " + index);

                // post the move
                this.postTheMove(index);
            }
        },

        async restartGame(uniqueGameUrl) {
            await axios
                .post("/tictactoe/restart_game", {
                    gameUrl: uniqueGameUrl,
                })
                .then(() => {
                    window.location.reload();
                });
        },

        async initializeChat(gameUrl) {
            await axios.post('/initialize-chat', {
                gameUrl: gameUrl
            }).then((response) => {
                // console.log(response);
            })
        },
    },
};
</script>
