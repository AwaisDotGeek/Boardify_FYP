<template>
    <div class="shadow-2xl shadow-black-5" :class="{ 'loader-container': isLoaderCardActive, 'inactive': !isLoaderCardActive  }">
        <span class="loader"></span>
        <h5>{{ loaderText }}</h5>
    </div>
</template>

<script>
    export default {
        props: ['loaderText', 'isLoaderCardActive'],   
    }
</script>

<style scoped>

    .inactive {
        display: none;
    }
    .loader-container {
        position: absolute;
        height: 150px;
        width: 250px;
        background: #212b2a;
        border-radius: 15px;

        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 10px;
    }

    h5 {
        color: #fff;
    }
    .loader {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: inline-block;
        border-top: 4px solid #FFF;
        border-right: 4px solid transparent;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    .loader::after {
        content: '';  
        box-sizing: border-box;
        position: absolute;
        left: 0;
        top: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border-left: 4px solid #FF3D00;
        border-bottom: 4px solid transparent;
        animation: rotation 0.5s linear infinite reverse;
    }
    
    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    } 
</style>