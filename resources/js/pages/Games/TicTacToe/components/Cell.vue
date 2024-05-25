<template>
    <button
        class="rounded-3xl cell"
        :class="
            isValueEmpty
                ? 'shadow-inner shadow-black-5'
                : 'shadow-bubble transition-all bg-gradient-to-br from-gray-200 to-gray-500 animate-scaled'
        "
    >
        <span v-if="value" class="text-5xl font-bold text-white">
            <i v-if="value === 'X'" class="fa-solid fa-x text-shadow-red"></i>
            <i v-if="value === 'O'" class="fa-solid fa-o text-shadow-green"></i>
        </span>
    </button>
</template>

<script>
export default {
    props: ["value"],
    computed: {
        isValueEmpty() {
            return this.value === null;
        },
    },
};
</script>

<style scoped>
.cell {
    width: 100px;
    height: 100px;
}

.text-shadow-red {
    text-shadow:
        2px 2px 5px red,
        -2px -2px 5px yellow,
        0 2px 8px darkred,
        2px 0 5px red;
}

.text-shadow-green {
    text-shadow:
        2px 2px 5px darkblue,
        -2px -2px 5px deepskyblue,
        0 2px 5px cyan,
        2px 0 5px royalblue;
}

.animate-scaled {
    animation: scale 0.3s ease-in-out;
}

@keyframes scale {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(0.9);
    }
    75% {
        transform: scale(1.1);
    }
}
</style>
