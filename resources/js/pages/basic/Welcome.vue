<template>
    <div class="home-container">
        <div class="w-screen h-screen flex justify-center items-center">
            <img src="../../assets/bg.png" alt="" class="border-0">
        </div>
        <div class="absolute top-0 left-0 w-full h-full bg-black opacity-40"></div>

        <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center">
            <div class="bg-black-2 text-white p-8 rounded-xl opacity-95 grid place-items-center">
                <div class="w-20 h-20 mb-4 rounded-full overflow-hidden">
                    <img src="../../assets/logo.png" alt="">
                </div>
                <h3>With us, YOU play games!</h3>
                <div class="flex justify-center gap-2.5 mt-3">
                    <Link href="/signup">Signup</Link>
                    <Link href="/login">Login</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
</script>

<style scoped>

h3 {
    letter-spacing: 0.5px;
}

a {
    text-decoration: none;
    color: #fff;
    letter-spacing: 0.7px;
    background: #0e0f11;
    padding: 8px 10px;
    border-radius: 5px;

    width: 100px;
    text-align: center;
    transition: all 0.25s ease;
}

a:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 10px 2px #333738;
}

a:active {
    transform: translateY(2px);
}
</style>




