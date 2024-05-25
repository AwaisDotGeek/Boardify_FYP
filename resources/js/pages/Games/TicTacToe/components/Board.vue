<template>
    <div
        class="shadow-bubble board flex justify-center items-center flex-wrap gap-3 m-4 p-3 rounded-xl bg-gradient-to-bl from-gray-100 to-gray-500 relative"
    >
        <Cell
            v-for="(cell, index) in cells"
            :key="index"
            :value="cell"
            @click="handleCellClick(index)"
        />
        <WinnerDisplay :winner="winner"></WinnerDisplay>
    </div>
</template>

<script>
import Cell from "./Cell.vue";
import WinnerDisplay from "./WinnerDisplay.vue";

export default {
    props: ["cells", "winner"],
    methods: {
        handleCellClick(index) {
            // Emit a custom event to notify App.vue of the cell click
            this.$emit("cell-clicked", index);
            Echo.join("tictactoe").whisper("moveMade", { index });
        },
    },
    components: {
        WinnerDisplay,
        Cell,
    },
};
</script>

<style scoped>
.board {
    max-width: 350px;
}
</style>
