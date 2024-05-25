<template>
    <main class="w-[250px] h-[375.2px]">
        <!--  Home  -->
        <div ref="home" class="bg-green-500 p-7 min-w-[250px] h-[250px] border-[1px] border-black">
            <div class="flex justify-center items-center bg-white w-full h-full p-5">
                <ul class="grid grid-cols-2 gap-8">
                    <li v-for="index in 4" :key="index" class="w-12 h-12 p-1 border-2 rounded-full border-green-500">
                        <!-- Piece here -->
                        <Piece :color="'green'" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex">
            <!-- Squares for green home -->
            <ul>
                <li v-for="row in 3" :key="row" class="flex">
                    <div v-for="col in 6" :key="col"
                         class="min-w-[41.6px] max-w-[41.6px] min-h-[41.6px] max-h-[41.6px] border-[0.2px] border-black"
                         :class="{'bg-green-500': ((col !== 1 && row === 2) || col === 2 && row === 1), 'bg-white': !((col !== 1 && row === 2) || col === 2 && row === 1) }"
                    ></div>
                </li>
            </ul>
            <!-- Ending block -->
            <div class="ending-block min-w-[62.4px] h-[124.8px] bg-green-500">
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
    clip-path: polygon(0 0, 100% 50%, 0 100%, 0 0);
}
</style>
