<template>
    <main class="flex justify-center items-center w-fit" style="border: 10px groove grey">
        <!-- board -->
        <div>
            <div class="flex bg-pink-400">
                <GreenHome />
                <YellowHome />
            </div>
            <div class="flex">
                <RedHome class="" />
                <BlueHome class="mt-[-125px]" />
            </div>
        </div>
    </main>
</template>

<script>
import YellowHome from "./YellowHome.vue";
import GreenHome from "./GreenHome.vue";
import BlueHome from "./BlueHome.vue";
import RedHome from "./RedHome.vue";
export default {
    components: {
        RedHome,
        BlueHome,
        GreenHome,
        YellowHome,
    }
}
</script>
