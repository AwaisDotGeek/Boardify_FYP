<template>
    <div
        class="w-screen h-screen overflow-x-hidden flex justify-center items-center"
    >
        <div
            class="relative flex md:flex-row-reverse sm:w-full sm:h-full justify-center items-center"
        >
            <!-- The Form Img  -->
            <div
                class="md:relative md:w-1/2 md:h-full sm:absolute sm:top-0 sm:left-0 w-full h-full"
            >
                <img
                    src="../../assets/bg.png"
                    alt=""
                    class="w-full min-h-full object-cover"
                />
                <div
                    class="absolute top-0 left-0 w-full h-full bg-gray-950 opacity-30"
                ></div>
            </div>

            <!-- The Form -->
            <div
                class="flex justify-center items-center md:w-1/2 h-full md:static absolute w-full md:bg-gradient-to-tr from-black-2 to-black-4"
            >
                <form
                    action=""
                    @submit.prevent="submitData"
                    class="flex justify-center items-center bg-gradient-to-tr from-black-2 to-black-4 rounded-3xl md:rounded-2xl p-10 sm:rounded-2xl sm:bg-opacity-95 max-w-sm md:mx-3 lg:max-w-lg md:shadow-xl md:shadow-black-4"
                >
                    <h1 class="text-4xl text-white mb-6 font-semibold">
                        Sign up
                    </h1>

                    <FormGroup
                        @inputValueChange="handleUserName"
                        icon="fa-user fa-solid"
                        label="Username"
                        type="text"
                        required
                        :errorMsg="errors.username"
                        focused
                    >
                    </FormGroup>

                    <FormGroup
                        @inputValueChange="handleEmail"
                        icon="fa-envelope fa-solid"
                        label="Email"
                        type="email"
                        required
                        :errorMsg="errors.email"
                    >
                    </FormGroup>

                    <FormGroup
                        @inputValueChange="handlePassword"
                        icon="fa-lock fa-solid"
                        label="Password"
                        type="password"
                        required
                        :errorMsg="errors.password"
                    >
                    </FormGroup>

                    <!-- Submit button -->
                    <div class="w-full mt-2">
                        <button
                            class="w-full py-4 text-white text-lg cursor-pointer rounded-lg bg-gradient-to-bl from-black-2 via-black-4 to-black-5 shadow-md shadow-black-4 hover:-translate-y-1 hover:shadow-xl hover:shadow-black-4 active:translate-y-1 transition-all disabled:bg-gradient-to-l disabled:from-black disabled:to-black"
                            type="submit"
                            :disabled="form.processing"
                        >
                            Signup
                        </button>
                    </div>

                    <!-- Link to Login -->
                    <div class="mt-4">
                        <Link
                            href="/login"
                            class="text-gray-400 hover:text-gray-200 hover:underline"
                            >Already have an account?</Link
                        >
                    </div>
                </form>
            </div>
        </div>

        <LoaderCard
            :loaderText="loaderText"
            :isLoaderCardActive="isLoaderCardActive"
        ></LoaderCard>
    </div>
</template>

<script>
/* eslint-disable no-unused-vars */
import LoaderCard from "../../shared/LoaderCard.vue";
import FormGroup from "../../shared/FormGroup.vue";
import { Link } from "@inertiajs/vue3";
export default {
    data() {
        return {
            form: {
                password: "",
                username: "",
                email: "",
                processing: false,
            },

            loaderText: "",
            isLoaderCardActive: false,
        };
    },

    props: {
        errors: Object,
    },

    components: {
        LoaderCard,
        FormGroup,
        Link,
    },

    methods: {
        handleUserName(inputValue) {
            this.form.username = inputValue;
        },

        handleEmail(inputValue) {
            this.form.email = inputValue;
        },

        handlePassword(inputValue) {
            this.form.password = inputValue;
        },

        submitData() {
            this.$inertia.post("/signup", this.form, {
                onStart: () => {
                    this.form.processing = true;
                    this.isLoaderCardActive = true;
                    this.loaderText = "Creating Account";
                },
                onFinish: () => {
                    this.form.processing = false;
                    this.isLoaderCardActive = false;
                    this.loaderText = "Creating Account";
                },
            });
        },
    },
};
</script>
