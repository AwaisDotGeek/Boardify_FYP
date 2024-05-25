<template>
    <div
        class="border-b border-gray-300 flex items-center w-full h-12 px-2 py-1 overflow-hidden"
        :class="classes"
    >
        <i class="fa-regular text-gray-400 text-xl" :class="icon"></i>

        <div class="flex flex-1 items-center relative">
            <label
                class="transform -translate-y-3 text-gray-300 text-sm w-full absolute left-2"
                >{{ label }}</label
            >
            <input
                :type="type"
                v-model="inputFieldValue"
                autocomplete="off"
                :required="required"
                :disabled="disabled"
                class="w-full p-2 mt-4 text-white bg-transparent outline-none disabled:text-gray-400"
                ref="inputFieldRef"
                @change="handleInputValueChange"
            />

            <i
                class="cursor-pointer"
                :class="{ 'fa-solid fa-eye': type === 'password' }"
                :style="{
                    color: '#fff',
                    visibility:
                        inputFieldValue.length > 0 ? 'visible' : 'hidden',
                }"
                @click="handleEyeClick"
            >
            </i>
        </div>
    </div>
    <span
        class="text-red-600 w-full text-sm -mt-3"
        style="white-space: pre-line"
        >{{ errorMsg }}</span
    >
</template>

<script>
export default {
    data() {
        return {
            inputFieldValue: "",
        };
    },
    props: {
        icon: String,
        label: String,
        type: {
            type: String,
            default: "text",
        },
        errorMsg: String,
        required: Boolean,
        focused: Boolean,
        disabled: Boolean,
        value: {
            type: String,
            default: "",
        },
        classes: '',
    },

    created() {
        setTimeout(() => {
            if (this.value) {
                this.inputFieldValue = this.value;
            }
        }, 500);
    },

    methods: {
        handleInputValueChange(event) {
            if (this.type !== 'file') {
                this.$emit('inputValueChange', this.inputFieldValue)
            } else {
                this.$emit('inputValueChange', event)
            }
        },

        handleEyeClick: function (e) {
            if (e.target.classList.contains("fa-eye")) {
                e.target.classList.remove("fa-eye");
                e.target.classList.add("fa-eye-slash");
                this.$refs.inputFieldRef.type = "text";
            } else {
                e.target.classList.add("fa-eye");
                e.target.classList.remove("fa-eye-slash");
                this.$refs.inputFieldRef.type = `password`;
            }
        },
    },
};
</script>

<style scoped>
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
textarea:-webkit-autofill,
textarea:-webkit-autofill:hover,
textarea:-webkit-autofill:focus,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus {
    border: none;
    -webkit-text-fill-color: #fff;
    -webkit-box-shadow: 0 0 0 1000px transparent inset;
    transition: background-color 5000s ease-in-out 0s;
}
</style>
