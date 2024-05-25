<template>
    <main class="flex w-[375.2px] h-[250px]">
        <div class="flex-col">
            <!-- Squares for yellow home -->
            <ul>
                <li v-for="row in 6" :key="row" class="flex">
                    <div v-for="col in 3" :key="col"
                         class="w-[41.6px] h-[41.6px] bg-white border-[0.1px] border-black"
                         :class="{'bg-yellow-500': (col === 2 && row !== 1) || col === 3 && row === 2}"
                    ></div>
                </li>
            </ul>
            <!-- Ending block -->
            <div class="ending-block w-[124.8px] h-[62.4px] bg-yellow-500">
            </div>
        </div>
        <!--  Home  -->
        <div ref="home" class="bg-yellow-500 p-7 min-w-[250px] h-[250px] border-[1px] border-black">
            <div class="flex justify-center items-center bg-white w-full h-full p-5">
                <ul class="grid grid-cols-2 gap-8">
                    <li v-for="index in 4" :key="index" class="w-12 h-12 p-1 border-2 rounded-full border-yellow-500">
                        <!-- Piece here -->
                        <Piece :color="'yellow'" />
                    </li>
                </ul>
            </div>
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
    clip-path: polygon(0 0, 100% 0, 50% 100%, 50% 100%);
}
</style>
