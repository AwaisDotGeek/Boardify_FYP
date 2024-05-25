<template>
    <div
        class="lg:block hidden w-[350px] h-[100%] text-gray-400 p-2 rounded-md overflow-x-hidden shadow-sm shadow-black"
    >
        <h2 class="flex gap-9 mb-2">
            <span class="text-white font-bold">{{
                player1 && player1.color === "white"
                    ? player1 && player1.username
                    : player2 && player2.username
            }}</span>
            vs
            <span class="text-white font-bold">{{
                player2 && player2.color === "black"
                    ? player2 && player2.username
                    : player1 && player1.username
            }}</span>
        </h2>

        <!-- -->
        <ul class="">
            <li
                class="flex items-center px-2 rounded-sm"
                :class="{
                    'bg-gradient-to-bl from-black-4 to-black-1':
                        (index + 1) % 2 === 0,
                    'bg-gradient-to-bl from-black-1 to-black-4':
                        (index + 1) % 2 !== 0,
                }"
                v-for="(move, index) in moves"
                :key="index"
            >
                <span class="h-[24px] w-[24px]">{{ index + 1 }}. &nbsp;</span>

                <!-- The white piece which is moved -->
                <span class="flex w-32">
                    <img
                        class="w-[20px] h-[20px]"
                        :src="move.whitePieceImg"
                        alt=""
                    />
                    {{ move.whiteMove }}
                </span>

                <!-- The black piece which is moved -->
                <span class="flex" v-if="move.blackPieceImg">
                    <img
                        class="w-[22px] h-[22px]"
                        :src="move.blackPieceImg"
                        alt=""
                    />
                    {{ move.blackMove }}
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ["moves", "player1", "player2"],

    created() {
        console.log(this.moves);
    },
};
</script>

<style scoped></style>
