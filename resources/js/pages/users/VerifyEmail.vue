<template>
    <div class="w-screen h-screen flex flex-col justify-center items-center bg-gradient-to-bl from-black-2 to-black-4">
        <span class="text-green-500 mb-2" v-if="verificationCodeRequested">You will recieve verification code shortly!</span>
        <div class="shadow-2xl shadow-black-4 sm:w-3/5 bg-gradient-to-tr from-black-2 to-black-4 p-8 rounded-3xl max-w-lg">
            <h1 class="text-white mb-3 text-3xl font-semibold">Verify Email</h1>
            <form @submit.prevent="_submitVerificationCode">
                <FormGroup
                    @inputValueChange="handleVerificationCode"
                    icon="fa-solid fa-certificate"
                    label="Verification code"
                    type="text"
                    :errorMsg="verificationCodeError">
                    required
                </FormGroup>

                <div class="flex justify-between text-gray-300">
                    <span class="text-sm text-gray-300 hover:underline cursor-pointer" @click="getCodeAgain">Get code again</span>
                    <span :class="{ 're-code-wait': shouldWaitMore}" style="transition: all 0.05s ease-in-out;">0:{{ timer }}</span>
                </div>

                <input
                    :disabled="processing"
                    type="submit"
                    value="Verify"
                    class="w-full py-4 text-white text-lg cursor-pointer rounded-lg bg-gradient-to-bl from-black-2 via-black-4 to-black-5 shadow-md shadow-black-4 hover:-translate-y-1 hover:shadow-xl hover:shadow-black-4 active:translate-y-1 transition-all"
                />

            </form>

            <ul class="text-gray-400 px-2">
                <li class="list-disc">
                    Check your <span class="text-gray-300">email</span> for verification code.
                </li>
                <li class="list-disc">
                    Please also check your <span class="text-gray-300">spam folder</span> if you can't find email in your inbox.
                </li>
            </ul>
        </div>

        <LoaderCard :loaderText="loaderText" :isLoaderCardActive="isLoaderCardActive"></LoaderCard>
    </div>
</template>

<script>
    import LoaderCard from '../../shared/LoaderCard.vue';
    import FormGroup from '../../shared/FormGroup.vue';
    import { Link } from '@inertiajs/vue3';
    export default {
        props: {
            userId: String,
            errors: Object, // Returned in inertia props
        },
        components: {
            LoaderCard,
            FormGroup,
            Link,
        },
        data() {
            return {
                verificationCode: "",
                verificationCodeError: "",
                processing: false,
                verificationCodeRequested: false,

                timer: 30,
                shouldWaitMore: false,

                // Loader card
                loaderText: "",
                isLoaderCardActive: false,
            };
        },
        mounted() {
            setTimeout(() => {
                setInterval(() => {
                    if (this.timer > 0) {
                        this.timer--;
                    }
                }, 1000);
            }, 1000);
        },

        methods: {
            handleVerificationCode(inputValue) {
                this.verificationCode = inputValue;
            },

            _submitVerificationCode() {
                let form = {
                    userId: this.userId,
                    verification_code: this.verificationCode,
                }
                this.$inertia.post('/verify-email', form, {
                    onStart: () => {
                        this.processing = true;
                        this.isLoaderCardActive = true;
                        this.loaderText = 'Creating Account';
                    },
                    onFinish: () => {
                        this.isLoaderCardActive = false;
                        this.loaderText = 'Creating Account';
                        this.processing = false;

                        if (this.errors) {
                            this.verificationCodeError = this.errors.verification_code;
                        }
                    }
                });
            },

            getCodeAgain() {
                if (this.timer > 0) {
                    let myInterval = null;
                    myInterval = setInterval(() => {
                        this.shouldWaitMore = !this.shouldWaitMore;
                    }, 200);

                    setTimeout(() => {
                        clearInterval(myInterval);
                        this.shouldWaitMore = false;
                    }, 2000);
                } else {
                    this.timer = 60;
                    this.requestCode();
                }
            },

            requestCode() {
                this.loaderText = "Requesting Code...";
                this.isLoaderCardActive = true;

                setTimeout(() => {
                    this.verificationCodeRequested = true;
                    this.isLoaderCardActive = false;
                }, 3500);

                this.$inertia.post('/get_verification_code');

                setTimeout(() => {
                    this.verificationCodeRequested = false;
                }, 6500);
            }

        }
    };
</script>

<style scoped>

    .re-code-wait {
        font-weight: bolder;
        color: #fff;
        letter-spacing: 2px;
    }

</style>
