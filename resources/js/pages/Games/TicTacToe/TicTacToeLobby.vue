<template>
    <main class="h-screen w-screen p-10 flex flex-col items-center">
        <div class="text-gray-200 text-bold">
            <h2 class="text-xl">Welcome to the Lobby</h2>
            <br />
            <h2 class="text-4xl">{{ user.username }}</h2>
        </div>
        <h1 class="text-gray-200 mt-3">
            {{ users.length }} {{ users.length > 1 ? "players" : "player" }} in
            Lobby
        </h1>
    </main>
</template>

<script>
import axios from "axios";
import { usePlayersStore } from "./stores/PlayersStore.js";

export default {
    data() {
        return {
            users: [],
            playersStore: usePlayersStore(),
        };
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },
    created() {
        this.startGame();

        let userCount = 0;
        const lobbyChannel = "tictactoe_lobby";

        Echo.join(lobbyChannel)
            .here((users) => {
                userCount = users.length;
                this.users = users;
                console.log("Total users here: ", userCount);
            })
            .joining((user) => {
                userCount++;
                this.users.push(user);
                console.log("User joined. Total users: ", this.users.length);
            })
            .listen("message", () => {})
            .leaving((user) => {
                userCount--;
                this.users = this.users.filter((u) => u.id !== user.id);
                console.log("User left. Total users: ", userCount);
            });
    },

    methods: {
        async startGame() {
            const response = await axios.post("/tictactoe/start-game");
            console.log("Response => ", response.data.message);

            // for this player
            if (response.data.status) {
                window.location.href = response.data.gameUrl;
            }

            // For other player
            await this.$nextTick(() => {
                Echo.channel("tictactoe_channel").listen(
                    "GameStarted",
                    (event) => {
                        // const players = event.players;
                        window.location.href = event.gameUrl;
                    },
                );
            });
        },
    },
};
</script>
