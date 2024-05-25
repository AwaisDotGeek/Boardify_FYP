<template>
    <div :class="{ 'message-card': this.isMsgCardActive, 'inactive': !this.isMsgCardActive }">
        <h2>{{ msgText }}</h2>
        <div class="btn-container" v-if="btnText">
            <Button>
                <template v-slot:btn-value>
                    {{ btnText }}
                </template>
            </Button>
        </div>
        <h4>{{ msgText2 }}</h4>
    </div>
</template>

<script>
    import Button from './Button.vue';
    export default {
        props: ['msgText', 'msgText2', 'btnText', 'isMsgCardActive'],
        components: {
            Button,
        },
    }
</script>

<style scoped>
    .message-card {
        background: #212b2a;
        height: 150px;
        padding: 25px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        justify-content: center;
        box-shadow: 0 0 10px 0 #fff;

        position: absolute;
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .inactive {
        display: none;
    }

    .message-card > h2 {
        color: #fff;
    }

    .message-card > h4 {
        color: #fff;
        text-align: center;
    }
</style>