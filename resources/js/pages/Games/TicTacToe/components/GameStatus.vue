<template>
    <div class="game-status text-2xl">
        <p v-if="!winner">Current Player: {{ currentPlayer }}</p>
    </div>
</template>

<script>
import WinnerDisplay from "./WinnerDisplay.vue";
export default {
    props: ["currentPlayer", "winner"],
    components: {
        WinnerDisplay,
    },
};
</script>
