<template>
    <main class="w-screen h-screen">
        <div class="w-full h-full relative">
            <img class="max-w-full max-h-full object-cover" src="../../../assets/chess.jpg" alt="">

            <div class="absolute top-0 w-full h-full flex justify-center items-center">
                <button @click="count > 1 ? count-- : count = 3" class="text-white text-xl bg-gradient-to-l from-black-1 to-black-4 ml-0.5 px-2 h-[100px] rounded-full"><i class="fa-solid fa-arrow-left"></i></button>

                <div v-if="count===1" class="w-[80%] max-w-[700px] bg-gradient-to-tr from-black-1 to-black-3 p-10 rounded-lg shadow-md shadow-gray-500 flex justify-center gap-5">
                    <p class="text-xl text-white font-bold py-2 px-4">
                        Play a 10 minute Multiplayer Game
                    </p>
                    <a href="/chess/lobby" class="bg-red-600 px-10 py-2 rounded-full text-white text-xl font-bold bg-gradient-to-tr from-black-5 to-black-1 shadow-sm shadow-gray-500 hover:shadow-xl active:shadow-lg">Play</a>
                </div>

<!--                <div v-if="count===2" class="w-[80%] max-w-[700px] bg-gradient-to-tr from-black-1 to-black-3 p-10 rounded-lg shadow-md shadow-gray-500 flex justify-center gap-5">-->
<!--                    <p class="text-xl text-white font-bold py-2 px-4">-->
<!--                        Play a Game with AI-->
<!--                    </p>-->
<!--                    <a href="/chess/AI" class="bg-red-600 px-10 py-2 rounded-full text-white text-xl font-bold bg-gradient-to-tr from-black-5 to-black-1 shadow-sm shadow-gray-500 hover:shadow-xl active:shadow-lg">Play</a>-->
<!--                </div>-->

                <div v-if="count===2" class="w-[80%] max-w-[700px] bg-gradient-to-tr from-black-1 to-black-3 p-10 rounded-lg shadow-md shadow-gray-500 flex justify-center gap-5">
                    <p class="text-xl text-white font-bold py-2 px-4">
                        Solve Chess Puzzles
                    </p>
                    <a href="/chess/puzzles" class="bg-red-600 px-10 py-2 rounded-full text-white text-xl font-bold bg-gradient-to-tr from-black-5 to-black-1 shadow-sm shadow-gray-500 hover:shadow-xl active:shadow-lg">Play</a>
                </div>

                <button @click="count < 3 ? count++ : count = 1" class="text-white text-xl bg-gradient-to-r from-black-1 to-black-4 ml-0.5 px-2 h-[100px] rounded-full"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            count: 1,
        }
    }
}

</script>
