<template>
    <section
        :id="id"
        class="border-2 border-black-2 shadow-lg shadow-black-5 flex gap-4 rounded-xl p-5 max-w-6xl"
    >
        <div class="w-1/2 overflow-hidden rounded-xl">
            <img
                :src="getImgSrc"
                alt=""
                class="w-full h-full object-cover transition-all brightness-75 hover:brightness-100 hover:scale-110"
            />
        </div>
        <div class="game-details-container">
            <div class="game-text-container">
                <h3 class="game-details-heading">
                    <span style="font-size: x-large; color: #fff"
                        >{{ gameName }}:</span
                    >
                    {{ gameHeadText }}
                </h3>
                <p class="game-text">
                    <slot name="gameText"></slot>
                </p>
            </div>
            <div class="game-btns-container">
                <button class="game-btn learn-btn">Let's Learn</button>
                <a class="game-btn play-btn text-center" :href="'/' + gameName.toLowerCase().replaceAll(' ', '') + '/play'"
                    >Let's Play</a
                >
            </div>
        </div>
    </section>
</template>

<script>
import chessImage from "../assets/chess.jpg";
import ludoImage from "../assets/ludo.jpg";
import rangImage from "../assets/rang.jpg";
import snackers from "../assets/snackers.jpg";
import ttt from "../assets/tic_tac_toe.jpg";
export default {
    props: ["id", "gameName", "gameHeadText", "animationStyle"],
    computed: {
        getImgSrc() {
            switch (this.gameName) {
                case "Chess":
                    return chessImage;
                case "Ludo":
                    return ludoImage;
                case "Rang":
                    return rangImage;
                case "Snakes and Ladders":
                    return snackers;
                case "Tic Tac Toe":
                    return ttt;
            }
            return ludoImage;
        },
    },
    methods: {
        playGame() {
            this.$inertia.replace("/tictactoe");
        },
    },
};
</script>

<style scoped>
.game-details-container {
    padding-right: 10px;
    width: 50%;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 50px;
}

.game-details-heading {
    color: #eee;
    margin-bottom: 5px;
}
.game-text {
    text-align: justify;
    color: #fff;
    font-size: 16.5px;
    letter-spacing: 0.3px;
    margin-top: 15px;
    line-height: 1.3;
}

.game-img-container {
    max-width: 50%;
    border-radius: 10px;
    overflow: hidden;
}

.game-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    filter: brightness(0.7); /* Adjust the brightness of the image */
    transition:
        transform 0.3s,
        filter 0.3s;
}

.game-img:hover {
    transform: scale(1.05); /* Add a slight scale effect on hover */
    filter: brightness(1);
}

.game-btns-container {
    align-self: flex-end;

    display: flex;
    gap: 8px;
}

.game-btn {
    width: 150px;
    padding: 15px;
    border-radius: 10px;
    border: none;
    font-weight: bold;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.play-btn {
    background: #030f11;
    color: #fff;
}

.play-btn:hover {
    box-shadow: 0 0 3px 0 #fff;
    transform: translateY(-2px);
}

.learn-btn {
    background: transparent;
    color: #fff;
    border: 1px solid #ccc;
    position: relative;
    overflow: hidden;
}

.learn-btn::before {
    content: "Let's Learn";
    opacity: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    position: absolute;
    width: 0;
    height: 100%;
    left: 0;
    top: 0;
    background: #fff;
    transition: all 0.3s ease-in-out;
}
.learn-btn:hover {
    color: #000;
    /* transform: translateY(-2px); */
}

.learn-btn:hover::before {
    width: 100%;
    opacity: 1;
}

@media screen and (max-width: 768px) {
    .game-section {
        margin: 20px 3%;
    }

    .game-img {
        min-height: 100%;
    }

    .game-text {
        font-size: 14.5px;
    }
}
</style>
