<template>
    <div
        class="absolute mt-[-5px] z-[2] h-full w-full flex justify-center items-center"
    >
        <img :src="imgSrc" :alt="imgAlt" />
    </div>
</template>

<script>
export default {
    props: ["imgSrc", "imgAlt"],
};
</script>

<style scoped>
img {
    width: 80%;
    height: 80%;
    object-fit: cover;
}
</style>
