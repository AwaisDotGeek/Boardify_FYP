<template>
    <!-- Main -->
    <div class="w-screen h-screen overflow-x-hidden flex justify-center items-center">
        <!-- Container -->
        <div class="relative flex md:flex-row-reverse sm:w-full sm:h-full justify-center items-center">
            <!-- Form Image -->
            <div class="md:relative md:w-1/2 md:h-full sm:absolute sm:top-0 sm:left-0 w-full h-full">
                <img src="../../assets/bg.png" alt="" class="w-full min-h-full object-cover">
                <div class="absolute top-0 left-0 w-full h-full bg-gray-950 opacity-30"></div>
            </div>

            <!-- Form Container -->
            <div class="flex justify-center items-center md:w-1/2 h-full md:static absolute w-full md:bg-gradient-to-tr from-black-2 to-black-4">
                <form
                    @submit.prevent="submitData"
                    class="flex justify-center items-center bg-gradient-to-tr from-black-2 to-black-4 rounded-3xl md:rounded-2xl p-10 sm:rounded-2xl sm:bg-opacity-95 max-w-sm md:mx-3 lg:max-w-lg md:shadow-xl md:shadow-black-4"
                >

                    <h1 class="text-4xl text-white mb-6 font-semibold">Reset Password</h1>

                    <FormGroup
                        v-if="!showNewPassword"
                        @inputValueChange="handleEmail"
                        icon="fa-envelope fa-solid"
                        label="Email" type="email"
                        :errorMsg="errors.email"
                        :disabled="success"
                        focused>
                    </FormGroup>

                    <FormGroup
                        @inputValueChange="handleVerificationCode"
                        v-if="emailFoundInRecords && !showNewPassword"
                        icon="fa-solid fa-certificate"
                        label="Verification code"
                        type="text"
                        focused
                        :errorMsg="errors.verification_code">
                    </FormGroup>

                    <FormGroup
                        v-if="showNewPassword"
                        @inputValueChange="handleNewPassword"
                        icon="fa-solid fa-lock"
                        label="New password"
                        type="password"
                        :errorMsg="errors.password">
                    </FormGroup>

                    <!-- Submit Button -->
                    <div class="w-full mt-2">
                        <input
                            :disabled="form.processing"
                            type="submit"
                            :value="submitBtnValue"
                            class="w-full py-4 text-white text-lg cursor-pointer rounded-lg bg-gradient-to-bl from-black-2 via-black-4 to-black-5 shadow-md shadow-black-4 hover:-translate-y-1 hover:shadow-xl hover:shadow-black-4 active:translate-y-1 transition-all disabled:bg-gradient-to-l disabled:from-black disabled:to-black"
                        >
                    </div>

                    <!-- Login Option -->
                    <div class="mt-4">
                        <p class="text-gray-400">
                            remember password?
                            <Link href="/login" class="text-gray-300 hover:text-gray-100 hover:underline">
                                Login
                            </Link>
                            now
                        </p>
                    </div>

                </form>
            </div>
        </div>
        <LoaderCard :loaderText="loaderText" :isLoaderCardActive="isLoaderCardActive"></LoaderCard>
    </div>
</template>

<script>
import LoaderCard from '../../shared/LoaderCard.vue';
import FormGroup from '../../shared/FormGroup.vue';
import {Link} from "@inertiajs/vue3";

export default {
    components: {
        LoaderCard,
        FormGroup,
        Link,
    },
    props: {
        errors: Object,
        success: Boolean,
    },
    data() {
        return {
            form: {
                step: 1, // counting step on which user is.
                email: '',
                verification_code: '',
                password: '',
                processing: false,
            },
            showNewPassword: false,
            emailFoundInRecords: false,
            submitBtnValue: 'Check',

            // Loader Card
            loaderText: "",
            isLoaderCardActive: false,
        }
    },

    methods: {
        handleEmail(inputValue) {
            this.form.email = inputValue;
        },
        handleVerificationCode(inputValue) {
            this.form.verification_code = inputValue;
        },
        handleNewPassword(inputValue) {
            this.form.password = inputValue;
        },

        submitData() {
            // agr email ab tk verify nhi hoi.
            this.$inertia.post('/reset-password', this.form, {
                onStart: () => {
                    this.loaderText = "Checking Account";
                    this.isLoaderCardActive = true;
                    this.form.processing = true;
                },
                onFinish: () => {
                    this.form.processing = false;
                    this.isLoaderCardActive = false;
                    if (this.success && this.form.step === 1) { // success comes from backend
                        this.emailFoundInRecords = true;
                        this.submitBtnValue = "Verify";
                        this.form.step = 2;
                    } else if (this.success && this.form.step === 2) {
                        this.submitBtnValue = 'Save';
                        this.showNewPassword = true;
                        this.form.step = 3;
                    }
                }
            });

        },
    }
}
</script>

<style scoped>
</style>
