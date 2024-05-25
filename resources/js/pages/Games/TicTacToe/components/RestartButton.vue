<template>
    <button class="restart-button" @click="restartGame">Restart Game</button>
</template>

<script>
export default {
    methods: {
        restartGame() {
            this.$emit('handleRestartClick');
        },
    },
};
</script>
