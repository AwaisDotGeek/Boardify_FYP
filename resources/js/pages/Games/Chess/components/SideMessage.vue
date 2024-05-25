<template>
    <div class="mb-5 shadow-xl shadow-black-2">
        <div class="flex justify-center items-center gap-3 bg-gradient-to-tl from-black-3 to-black-4 p-3 rounded-lg max-w-[250px]">
            <i class="fas fa-info flex justify-center items-center bg-white min-w-[32px] h-[32px] rounded-full text-xl"></i>
            <p class="text-left">
                <slot></slot>
            </p>
        </div>
    </div>
</template>
