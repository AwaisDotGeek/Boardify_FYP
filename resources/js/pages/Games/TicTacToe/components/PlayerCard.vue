<template>
    <main
        :style="{
            borderTopWidth:
                isBorderOnTop &&
                currentPlayer &&
                currentPlayer.symbol &&
                currentPlayer.symbol === playerSymbol
                    ? '2.5px'
                    : '0',
            borderBottomWidth:
                !isBorderOnTop &&
                currentPlayer &&
                currentPlayer.symbol &&
                currentPlayer.symbol === playerSymbol
                    ? '2.5px'
                    : '0',
        }"
        class="w-full p-2 rounded-md border-white"
        :class="{}"
    >
        <!--        class="w-full p-2 rounded-xl bg-gradient-to-tr from-black-2 to-black-4"-->

        <div class="flex gap-3 items-start">
            <!-- User's Profile photo -->
            <div class="w-12 h-12">
                <img
                    v-if="profile_photo"
                    :src="'../../' + profile_photo"
                    class="rounded-md w-full h-full object-cover"
                    alt=""
                />
                <img v-else src="../../../../assets/logo.png" class="rounded-md w-full h-full object-cover" alt="">
            </div>
            <div>
                <!-- Player Details -->
                <div class="flex items-center text-gray-200 gap-2 mb-1">
                    <h1 class="text-md">{{ user.username }}</h1>
                    <h2 v-if="playerSymbol" class="text-md">
                        ({{ playerSymbol }})
                    </h2>
                    <!-- X or O  -->
                    <!-- Country Flag -->
                    <div class="w-6 h-4" v-if="country">
                        <img
                            :src="'../../country-flags/' + country + '.png'"
                            class="w-full h-full object-cover"
                            alt=""
                        />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import axios from "axios";

export default {
    props: ["user", "playerSymbol", "isBorderOnTop", "currentPlayer"], // user (user playing), player (O or X)

    data() {
        return {
            country: '',
            profile_photo: '',
        }
    },

    created() {

        // Wait until this.user.user_id is defined
        const waitForUserId = () => {
            if (this.user && this.user.user_id) {
                // Once user_id is defined, call fetchUserDetails
                this.fetchUserDetails();
            } else {
                // If user_id is not defined, wait and check again
                setTimeout(waitForUserId, 100);
            }
        };

        // Start waiting for user_id
        waitForUserId();
    },

    methods: {
        async fetchUserDetails() {
            await axios.get('/user-details',  {
                params: {
                    user_id: this.user.user_id
                }
            })
                .then((response) => {
                    this.country = response.data.user.country;
                    this.profile_photo = response.data.user.photoUrl;
                    if (this.country) {
                        this.getCountryShortcut();
                    }
                    console.log(this.user);
                    console.log(response);
                })
                .catch((error) => {
                    console.error('Error fetching user details:', error);
                });
        },

        async getCountryShortcut() {
            await axios.get(`https://restcountries.com/v3.1/name/${this.country}`)
                .then((response) => {
                    this.country = response.data[0].altSpellings[0].toLowerCase();
                });
        },
    }
};
</script>
