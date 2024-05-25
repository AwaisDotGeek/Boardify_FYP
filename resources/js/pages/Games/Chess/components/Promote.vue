// This file is for Pawn promotion
<template>
    <div
        :class="{ hidden: !showPromotionOption, 'bg-black-3': color === 'w', 'top-[25%]': color === 'w', 'top-[60%]': color === 'b',  'rotate-180': color === 'b' }"
        class="absolute left-[25%] z-[5] flex gap-3 p-3 rounded-xl bg-gray-100"
    >
        <img @click="promote" class="img" :src="QueenSrc" alt="Queen" />
        <img @click="promote" class="img" :src="RookSrc" alt="Rook" />
        <img @click="promote" class="img" :src="BishopSrc" alt="Bishop" />
        <img @click="promote" class="img" :src="KnightSrc" alt="Knight" />
    </div>
</template>

<script>
export default {
    props: ["color", "showPromotionOption"],
    computed: {
        QueenSrc() {
            return `../assets/chess_pieces/${this.color}Q.svg`;
        },
        RookSrc() {
            return `../assets/chess_pieces/${this.color}R.svg`;
        },
        BishopSrc() {
            return `../assets/chess_pieces/${this.color}B.svg`;
        },
        KnightSrc() {
            return `../assets/chess_pieces/${this.color}N.svg`;
        },
    },

    methods: {
        promote(e) {
            const selected = e.target.getAttribute("alt");
            this.$emit("promoteTo", {
                selected,
            });
        },
    },
};
</script>

<style scoped>
.img {
    width: 45px;
    height: 45px;
    cursor: pointer;
}
</style>
