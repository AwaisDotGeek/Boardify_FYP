<template>
    <main class="h-screen w-screen p-10 flex flex-col items-center">
        <div class="text-gray-200 text-bold">
            <h2 class="text-xl">Welcome to the Lobby</h2>
            <br />
            <h2 class="text-4xl">{{ user.username }}</h2>
        </div>

        <div tabindex="0" @keydown="handleKeyPress" class="hidden relative overflow-hidden w-[650px] min-h-[350px] border-1 border-black bg-green-950 mt-5">
<!--            <div-->
<!--                :style="{ marginTop: topPosition + 'px', marginLeft: leftPosition + 'px'}"-->
<!--                class="relative w-[32px] h-[32px] bg-white rounded-full">-->
<!--                &lt;!&ndash; Wing &ndash;&gt;-->
<!--                <div class="animate-spin absolute w-[48px] h-[2px] bg-white left-[-8px] top-[-5px]"></div>-->
<!--                &lt;!&ndash; Wing Holder &ndash;&gt;-->
<!--                <div class="absolute w-[3px] h-[6px] bg-white top-[-5px] left-[45%] rounded-full"></div>-->
<!--                &lt;!&ndash; Back rounded &ndash;&gt;-->
<!--                <div class="absolute w-[16px] h-[16px] bg-white top-[8px] left-[-8px] rounded-md"></div>-->
<!--                &lt;!&ndash; Bottom holders &ndash;&gt;-->
<!--                <div class="absolute w-[3px] h-[6px] bg-white bottom-[-5px] left-[35%]"></div>-->
<!--                <div class="absolute w-[3px] h-[6px] bg-white bottom-[-5px] left-[50%]"></div>-->
<!--                &lt;!&ndash; Bottom &ndash;&gt;-->
<!--                <div class="absolute w-[28px] h-[2px] bg-white bottom-[-5px]"></div>-->
<!--            </div>-->

            <!-- Barriers -->
            <div
                :style="{ 'left': barrierOneLeft + 'px', 'top': barrierOneTop + 'px' }"
                class="absolute w-[50px] h-[100px] bg-red-500">
            </div>
            <div
                :style="{ 'left': barrierTwoLeft + 'px', 'top': barrierTwoTop + 'px' }"
                class="absolute w-[50px] h-[80px] bg-red-500">
            </div>
            <div
                :style="{ 'left': barrierThreeLeft + 'px', 'top': barrierThreeTop + 'px' }"
                class="absolute w-[50px] h-[120px] bg-red-500">
            </div>
            <div
                :style="{ 'left': barrierFourLeft + 'px', 'top': barrierFourTop + 'px' }"
                class="absolute w-[50px] h-[150px] bg-red-500">
            </div>
        </div>

<!--        <h1 class="text-gray-200 mt-3">-->
<!--            {{ users.length }} {{ users.length > 1 ? "players" : "player" }} in-->
<!--            Lobby-->
<!--        </h1>-->
    </main>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            users: [],

            // Chopper
            topPosition: 100,
            leftPosition: 20,

            // Barriers
            barrierOneLeft: 650,
            barrierOneTop: 0,

            barrierTwoLeft: 800,
            barrierTwoTop: 100,

            barrierThreeLeft: 1000,
            barrierThreeTop: 150,

            barrierFourLeft: 1200,
            barrierFourTop: 180,
        };
    },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },
    created() {
        this.startGame();

        this.moveBarriers();

        let userCount = 0;
        const lobbyChannel = "chess_lobby";

        Echo.join(lobbyChannel)
            .here((users) => {
                userCount = users.length;
                this.users = users;
                // console.log("Total users here: ", userCount);
            })
            .joining((user) => {
                userCount++;
                this.users.push(user);
                // console.log("User joined. Total users: ", this.users.length);
            })
            .listen("message", () => {})
            .leaving((user) => {
                userCount--;
                this.users = this.users.filter((u) => u.id !== user.id);
                // console.log("User left. Total users: ", userCount);
            });
    },

    methods: {
        async startGame() {
            const response = await axios.post("/chess/start-game");
            // console.log("Response => ", response.data.message);

            // for this player
            if (response.data.status) {
                window.location.href = response.data.gameUrl;
            }

            // For other player
            await this.$nextTick(() => {
                Echo.channel("chess_channel").listen("GameStarted", (event) => {
                    // const players = event.players;
                    window.location.href = event.gameUrl;
                });
            });
        },

        handleKeyPress(event) {
            let step = 10;

            switch (event.key) {
                case "ArrowUp":
                    this.topPosition = this.topPosition <= 0 ? this.topPosition : this.topPosition - step;
                    break;
                case "ArrowDown":
                    this.topPosition = this.topPosition >= 318 ? this.topPosition : this.topPosition + step;
                    break;
                case "ArrowLeft":
                    this.leftPosition = this.leftPosition <= 5 ? this.leftPosition : this.leftPosition - step;
                    break;
                case "ArrowRight":
                    this.leftPosition = this.leftPosition >= 480 ? this.leftPosition : this.leftPosition + step;
                    break;
            }

            // console.log(this.topPosition);
        },

        moveBarriers() {
            setInterval(() => {
                // Barrier 1
                if (this.barrierOneLeft <= -50) {
                    this.barrierOneLeft = 700;
                    // this.barrierOneTop = Math.floor(Math.random() * 100);
                } else {
                    this.barrierOneLeft = this.barrierOneLeft - 10;
                }

                // Barrier 2
                if (this.barrierTwoLeft <= -70) {
                    this.barrierTwoLeft = 800;
                    // this.barrierTwoTop = Math.floor(Math.random() * 200);
                } else {
                    this.barrierTwoLeft = this.barrierTwoLeft - 10;
                }

                // Barrier 3
                if (this.barrierThreeLeft <= -70) {
                    this.barrierThreeLeft = 800;
                    // this.barrierThreeTop = Math.floor(Math.random() * 200);
                } else {
                    this.barrierThreeLeft = this.barrierThreeLeft - 10;
                }

                // Barrier 4
                if (this.barrierFourLeft <= -70) {
                    this.barrierFourLeft = 800;
                    // this.barrierFourTop = Math.floor(Math.random() * 200);
                } else {
                    this.barrierFourLeft = this.barrierFourLeft - 10;
                }
            }, 100);
        },
    },
};
</script>
