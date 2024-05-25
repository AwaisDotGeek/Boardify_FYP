<template>
    <main class="flex items-end w-[375.2px] h-[250px]">
        <!--  Home  -->
        <div ref="home" class="bg-red-500 p-7 min-w-[250px] h-[250px] border-[0.5px] border-black">
            <div class="flex justify-center items-center bg-white w-full h-full p-5">
                <ul class="grid grid-cols-2 gap-8">
                    <li v-for="index in 4" :key="index" class="w-12 h-12 p-1 border-2 rounded-full border-red-500">
                        <!-- Piece here -->
                        <Piece :color="'red'" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex-col">
            <!-- Ending block -->
            <div class="ending-block w-[124.8px] h-[62.4px] bg-red-500">
            </div>
            <!-- Squares for red home -->
            <ul>
                <li v-for="row in 6" :key="row" class="flex">
                    <div v-for="col in 3" :key="col"
                         class="w-[41.6px] h-[41.6px] border-[0.5px] border-black"
                         :class="{'bg-red-500': ((col === 2 && row !== 6) || col === 1 && row === 5), 'bg-white': !((col === 2 && row !== 6) || col === 1 && row === 5)}"
                    ></div>
                </li>
            </ul>
        </div>
    </main>
</template>

<script>
import Piece from "../components/Piece.vue";

export default {
    components: {Piece},
}
</script>

<style scoped>
.ending-block {
    clip-path: polygon(50% 0, 50% 0, 100% 100%, 0 100%);
}
</style>
