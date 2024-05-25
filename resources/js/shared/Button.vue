<template>
    <button class="btn">
        <slot name="btn-value"></slot>
    </button>
</template>

<style scoped>
    .btn {
        padding: 10px 25px;
        font-weight: bold;
        letter-spacing: 0.4px;
        border: none;
        border-radius: 10px;
        background: #030f11;
        color: #fff;
        min-width: 100px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 3px 0 #fff;
    }

    .btn:active {
        transform: translateY(2px);
    }
</style>