<template>
    <div class="w-full h-full rounded-full shadow-bubble"
        :class="`bg-${color}-500`"
    >
    </div>
</template>

<script>
export default {
    props: ['color'],
}
</script>
