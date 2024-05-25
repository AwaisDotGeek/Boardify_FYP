<template>
    <div
        class="w-screen h-screen overflow-x-hidden flex justify-center items-center"
    >
        <div
            class="relative flex md:flex-row-reverse sm:w-full sm:h-full justify-center items-center"
        >
            <!-- Background Image -->
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

            <!-- Profile Form -->
            <div
                class="flex justify-center items-center md:w-1/2 h-full md:static absolute w-full md:bg-gradient-to-tr from-black-2 to-black-4"
            >
                <form
                    action=""
                    @submit.prevent="submitData"
                    class="flex flex-col justify-center items-center bg-gradient-to-tr from-black-2 to-black-4 rounded-3xl md:rounded-2xl p-10 sm:rounded-2xl sm:bg-opacity-95 max-w-sm md:mx-3 lg:max-w-lg md:shadow-xl md:shadow-black-4"
                >
                    <h1 class="text-4xl text-white mb-6 font-semibold">
                        Profile Settings
                    </h1>

                    <!-- Username -->
                    <FormGroup
                        @inputValueChange="handleUsername"
                        icon="fa-user fa-solid"
                        label="Username"
                        type="text"
                        required
                        :errorMsg="errors.username"
                        :value="userDetails.username"
                        disabled
                    >
                    </FormGroup>

                    <!-- Email -->
                    <FormGroup
                        @inputValueChange="handleEmail"
                        icon="fa-envelope fa-solid"
                        label="Email"
                        type="email"
                        :errorMsg="errors.email"
                        :value="userDetails.email"
                        disabled
                    >
                    </FormGroup>

                    <!-- Country -->
                    <FormGroup
                        @inputValueChange="handleCountry"
                        icon="fa-globe fa-solid"
                        label="Country"
                        type="text"
                        required
                        :errorMsg="errors.country"
                        :value="userDetails.country"
                    >
                    </FormGroup>

                    <!-- Profile Photo -->
                    <div :class="{'w-full flex justify-between': profilePhotoUrl !== ''}">
                        <FormGroup
                            @inputValueChange="handleProfilePhoto"
                            icon="fa-image fa-solid"
                            label="Profile Photo"
                            type="file"
                            accept="image/*"
                            :errorMsg="errors.profile_photo"
                            classes="min-w-max"
                        >
                        </FormGroup>

                        <img :class="{'hidden': profilePhotoUrl === ''}" class="max-w-[60px] max-h-[60px] object-cover rounded-lg" :src="profilePhotoUrl" alt="">
                    </div>

                    <!-- Submit Button -->
                    <div class="w-full mt-2">
                        <button
                            class="w-full py-4 text-white text-lg cursor-pointer rounded-lg bg-gradient-to-bl from-black-2 via-black-4 to-black-5 shadow-md shadow-black-4 hover:-translate-y-1 hover:shadow-xl hover:shadow-black-4 active:translate-y-1 transition-all disabled:bg-gradient-to-l disabled:from-black disabled:to-black"
                            type="submit"
                            :disabled="form.processing"
                        >
                            Save Changes
                        </button>
                    </div>

                    <!-- Link to Home -->
                    <div class="mt-4">
                        <Link
                            href="/"
                            class="text-gray-400 hover:text-gray-200 hover:underline"
                        >
                            Back to Home
                        </Link>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loader Card Component -->
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
import axios from "axios";
import {data} from "autoprefixer";
export default {
    data() {
        return {
            profilePhotoUrl: '',

            form: {
                username: "",
                email: "",
                password: "",
                country: "",
                profile_photo: null,
                processing: false,
            },
            userDetails: '',
            loaderText: "",
            isLoaderCardActive: false,
        };
    },

    props: {
        errors: Object,
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    components: {
        LoaderCard,
        FormGroup,
        Link,
    },

    created() {
        this.fetchUserDetails();
    },

    methods: {
        handleUsername(inputValue) {
            this.form.username = inputValue;
        },

        handleEmail(inputValue) {
            this.form.email = inputValue;
        },

        handleCountry(inputValue) {
            this.form.country = inputValue;
        },

        handleProfilePhoto(event) {
            // console.log(event);
            // this.form.profile_photo = event.target.files[0];

            const file = event.target.files[0];
            this.form.profile_photo = file;
            console.log(this.form.profile_photo);

            // Convert the selected file to a Base64-encoded string
            // const reader = new FileReader();
            // reader.onload = () => {
            //     this.profilePhotoUrl = reader.result;
            // };
            // reader.readAsDataURL(file);
        },

        async fetchUserDetails() {
            await axios.get('/user-details', {
                params: {
                    user_id: this.user.user_id,
                }
            })
                .then((response) => {
                    this.userDetails = response.data.user;
                    this.form.username = this.userDetails.username;
                    this.form.email = this.userDetails.email;
                    this.form.country = this.userDetails.country;
                    this.profilePhotoUrl = this.userDetails.photoUrl;

                    console.log(this.profilePhotoUrl);
                })
                .catch((error) => {
                    console.error('Error fetching user details:', error);
                });
        },

        async submitData() {
            // Form submission logic
            console.log("Form submitted:", this.form);

            // Create FormData object
            const formData = new FormData();
            formData.append('username', this.form.username);
            formData.append('email', this.form.email);
            formData.append('country', this.form.country);
            formData.append('profile_photo', this.form.profile_photo);

            // Send FormData to the backend
            try {
                await axios.post('/save-user-details', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data' // Set content type to multipart/form-data for file upload
                    }
                });

                console.log("Successfully saved changes!");
                window.location.href = '/';
            } catch (error) {
                // Handle error
                console.error('Error submitting form data:', error);
            }
        }

    },
};
</script>
