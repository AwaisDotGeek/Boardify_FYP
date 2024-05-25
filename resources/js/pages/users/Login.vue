<template>
    <div class="w-screen h-screen overflow-x-hidden flex justify-center items-center">
        <div class="relative flex md:flex-row-reverse sm:w-full sm:h-full justify-center items-center">

            <div class="md:relative md:w-1/2 md:h-full sm:absolute sm:top-0 sm:left-0 w-full h-full">
                <img src="../../assets/bg.png" alt="" class="w-full min-h-full object-cover">
                <div class="absolute top-0 left-0 w-full h-full bg-gray-950 opacity-30"></div>
            </div>

            <div class="flex justify-center items-center md:w-1/2 h-full md:static absolute w-full md:bg-gradient-to-tr from-black-2 to-black-4">
                <form
                    class="flex justify-center items-center bg-gradient-to-tr from-black-2 to-black-4 rounded-3xl md:rounded-2xl p-10 sm:rounded-2xl sm:bg-opacity-95 max-w-sm md:mx-3 lg:max-w-lg md:shadow-xl md:shadow-black-4"
                    @submit.prevent="submitData"
                >
                    <h1 class="text-4xl text-white mb-6 font-semibold">Login</h1>

                    <FormGroup
                        @inputValueChange="handleEmail"
                        icon="fa-envelope fa-solid"
                        label="Email"
                        type="email"
                        required
                        :errorMsg="errors.email">
                    </FormGroup>
                    <FormGroup
                        @inputValueChange="handlePassword"
                        icon="fa-lock fa-solid"
                        label="Password"
                        type="password"
                        required
                        :errorMsg="errors.password">
                    </FormGroup>

                    <div class="w-full mt-2">
                        <input
                            class="w-full py-4 text-white text-lg cursor-pointer rounded-lg bg-gradient-to-bl from-black-2 via-black-4 to-black-5 shadow-md shadow-black-4 hover:-translate-y-1 hover:shadow-xl hover:shadow-black-4 active:translate-y-1 transition-all disabled:cursor-auto"
                            type="submit"
                            value="Login"
                            :disabled="form.processing"
                        >
                    </div>

                    <div class="mt-2 flex flex-col items-center">
                        <Link
                            class="text-gray-300 hover:underline hover:text-gray-200"
                            href="/reset-password">forgot password?
                        </Link>
                        <p class="mt-1.5">
                            don't have account?
                            <Link href="/signup">
                                <strong class="text-gray-300 hover:underline hover:text-gray-100">Signup</strong>
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
    import LoaderCard from "../../shared/LoaderCard.vue";
    import FormGroup from "../../shared/FormGroup.vue";
    import {Link, useForm} from "@inertiajs/vue3";

    export default {
        components: {
            LoaderCard,
            FormGroup,
            Link,
        },

        props: {
            errors: Object,
        },

        data() {
            return {
                form: {
                  email: '',
                  password: '',
                  processing: false,
                },

                loaderText: '',
                isLoaderCardActive: false,
            }
        },

        methods: {
            handleEmail(inputValue) {
                this.form.email = inputValue;
            },

            handlePassword(inputValue) {
                this.form.password = inputValue;
            },

            submitData() {
                this.$inertia.post('/login', this.form, {
                    onStart: () => {
                        this.form.processing = true;
                        this.loaderText = "Logging you in";
                        this.isLoaderCardActive = true;
                    },
                    onFinish: () => {
                        this.form.processing = false;
                        this.isLoaderCardActive = false;
                    },
                });
            },
        }
    }

// async sendVerificationCode(userId) {
//     try {
//         const response = await axios.post('/api/get-verification-code', {
//             userId: userId,
//         });
//     } catch (error) {
//         alert(error);
//     }
// }
</script>
