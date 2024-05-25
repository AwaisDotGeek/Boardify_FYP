<template>
    <main class="flex-col justify-end w-[250px] h-[375.2px]">
        <div class="flex ml-[-62.4px]">
            <!-- Ending block -->
            <div class="ending-block min-w-[62.4px] h-[124.8px] bg-blue-500">
            </div>
            <!-- Squares for blue home -->
            <ul>
                <li v-for="row in 3" :key="row" class="flex">
                    <div v-for="col in 6" :key="col"
                         class="min-w-[41.6px] max-w-[41.6px] min-h-[41.6px] max-h-[41.6px] border-[0.2px] border-black"
                         :class="{'bg-blue-500': ((col !== 6 && row === 2) || col === 5 && row === 3), 'bg-white': !((col !== 6 && row === 2) || col === 5 && row === 3)}"
                    ></div>
                </li>
            </ul>
        </div>
        <!--  Home  -->
        <div ref="home" class="bg-blue-500 p-7 min-w-[250px] h-[250px] border-[1px] border-black">
            <div class="flex justify-center items-center bg-white w-full h-full p-5">
                <ul class="grid grid-cols-2 gap-8">
                    <li v-for="index in 4" :key="index" class="w-12 h-12 p-1 border-2 rounded-full border-blue-500">
                        <!-- Piece here -->
                        <Piece :color="'blue'" />
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
    clip-path: polygon(100% 0, 0 50%, 100% 100%, 100% 0);
}
</style>
