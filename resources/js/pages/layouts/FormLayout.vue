<template>
    <div class="w-screen h-screen overflow-x-hidden flex justify-center items-center">
        <div
            class="relative flex md:flex-row-reverse sm:w-full sm:h-full justify-center items-center"
        >
            <!-- The Form Img  -->
            <div
                class="md:relative md:w-1/2 md:h-full sm:absolute sm:top-0 sm:left-0 w-full h-full"
            >
                <img src="../../assets/bg.png" alt="" class="w-full min-h-full object-cover"/>
                <div
                    class="absolute top-0 left-0 w-full h-full bg-gray-950 opacity-30"
                ></div>
            </div>

            <!-- The Form -->
            <div
                class="flex justify-center items-center md:w-1/2 h-full md:static absolute w-full md:bg-gradient-to-tr from-black-2 to-black-4"
            >
                <slot></slot>
            </div>
        </div>

        <LoaderCard
            :loaderText="loaderText"
            :isLoaderCardActive="isLoaderCardActive"
        ></LoaderCard>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import LoaderCard from "@/shared/LoaderCard.vue";
import FormGroup from "@/shared/FormGroup.vue";
</script>
