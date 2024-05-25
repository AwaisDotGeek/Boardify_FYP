<template>
    <div
        class="bg-black-3 text-gray-400 p-2 rounded-lg max-h-72 overflow-y-scroll overflow-x-hidden"
    >
        <h2>Game History</h2>
        <ul>
            <li v-for="(move, index) in moves" :key="index">
                {{ index + 1 }}: {{ move }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ["moves"],
};
</script>

<style scoped></style>
