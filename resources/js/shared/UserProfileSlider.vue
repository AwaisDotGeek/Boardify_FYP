<template>
    <div
        class="user-profile-slider mt-2 d-none"
        :class="{ 'd-block userProfileActive': isUserProfileCardActive }"
    >
        <div class="user-profile-slider-content-container">
            <div class="slider-user-details-container">
                <div class="slider-user-photo-container">
                    <img
                        v-if="profile_photo"
                        :src="profile_photo"
                        class="w-full h-full rounded-full"
                        alt=""
                    />
                    <img v-else src='../assets/logo.png' class="w-full h-full rounded-full" alt="">
                </div>
                <div class="slider-user-details" :class="{'flex gap-3': country}">
                    <h3 v-if="user && isUserProfileCardActive">{{ user.username }}</h3>
                    <div v-if="country">
                        <img class="max-w-[32px] max-h-[24px]" :src="'country-flags/' + country + '.png'" alt="">
                    </div>
                </div>
            </div>

            <div
                class="slider-tabs-container"
                :class="{ opacityZero: !isUserProfileCardActive }"
            >
                <div class="tab-ions-container">
                    <i
                        @click="handleSliderTabClick('tab1')"
                        class="fa-solid fa-gamepad"
                        :class="{ 'active-icon': activeSliderTab === 'tab1' }"
                    ></i>
                    <i
                        @click="handleSliderTabClick('tab2')"
                        class="fa-solid fa-user-group"
                        :class="{ 'active-icon': activeSliderTab === 'tab2' }"
                    ></i>
                    <i
                        @click="handleSliderTabClick('tab3')"
                        class="fa-solid fa-bell"
                        :class="{ 'active-icon': activeSliderTab === 'tab3' }"
                    ></i>
                    <i
                        @click="handleSliderTabClick('tab4')"
                        class="fa-solid fa-gear"
                        :class="{ 'active-icon': activeSliderTab === 'tab4' }"
                    ></i>
                </div>
                <div
                    class="tab-body-container tab-1"
                    :class="{ 'active-tab': activeSliderTab === 'tab1' }"
                >
                    <div class="tab-content-container">
                        <h3>Games History</h3>
                    </div>
                </div>
                <div
                    class="tab-body-container tab-2"
                    :class="{ 'active-tab': activeSliderTab === 'tab2' }"
                >
                    <div class="tab-content-container">
                        <h3>Friends</h3>
                    </div>
                </div>
                <div
                    class="tab-body-container tab-3"
                    :class="{ 'active-tab': activeSliderTab === 'tab3' }"
                >
                    <div class="tab-content-container">
                        <h3>Notifications</h3>
                    </div>
                </div>
                <div
                    class="tab-body-container tab-4"
                    :class="{ 'active-tab': activeSliderTab === 'tab4' }"
                >
                    <ul class="tab-content-container pl-3">
                        <li class="list-disc">
                            <Link href="/profile" class="text-gray-400 hover:text-white">Profile Settings</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import axios from "axios";
export default {
    props: {
        isUserProfileCardActive: Boolean,
    },

    components: {
        Link,
    },

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    data() {
        return {
            activeSliderTab: "tab1",

            profile_photo: '',
            country: '',

        };
    },

    created() {
        this.fetchUserDetails();
    },

    methods: {
        handleSliderTabClick(clickedTab) {
            if (clickedTab !== this.activeSliderTab) {
                this.activeSliderTab = clickedTab;
            }
        },

        async fetchUserDetails() {
            await axios.get('/user-details', {
                params: {
                    user_id: this.user.user_id,
                }
            })
                .then((response) => {
                    this.country = response.data.user.country;
                    this.profile_photo = response.data.user.photoUrl;
                    if (this.country) {
                        this.getCountryShortcut();
                    }

                    console.log("");
                    console.log(this.country);
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
    },
};
</script>

<style scoped>
.user-profile-slider {
    height: 0;
    width: 0;
    background: #333738;
    z-index: 3;
    border-radius: 0 0 0 15px;
    color: #fff;
    padding: 0;

    position: absolute;
    top: 75px;
    right: 0;

    transition:
        width 0.2s ease,
        height 0.4s ease-in-out,
        box-shadow 1.5s ease-in-out;
}

.userProfileActive {
    width: 380px;
    height: 600px;
    padding: 20px 10px 10px 10px;
}

.user-profile-slider-content-container {
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.slider-user-details-container {
    display: flex;
    gap: 10px;
}

.slider-user-photo-container {
    width: 85px;
    height: 85px;
}

.slider-user-photo-container > img {
    border-radius: 50%;
}

.slider-user-details {
    display: flex;
    align-items: center;
}

/* Slider tabs */
.slider-tabs-container {
    flex-grow: 1;
    opacity: 1;

    display: flex;
    flex-direction: column;

    transition: all 1.5s ease;
}

.opacityZero {
    opacity: 0;
}

.tab-ions-container {
    display: flex;
}

i {
    color: #ccc;
    flex-grow: 1;
    text-align: center;
    padding: 10px;
    border-radius: 8px 8px 0 0;
    font-size: 20px;
}

i:hover {
    color: #fff;
    cursor: pointer;
}

.active-icon {
    color: #fff;
    background: #212b2a;
}

.tab-body-container {
    flex-grow: 1;
    padding: 10px;
    border-radius: 0 0 10px 10px;
    background: #212b2a;
    display: none;
}

.active-tab {
    display: block;
}

/* ----------------------- */
/* ----------------------- */
/* ----------------------- */

h3 {
    letter-spacing: 0.3px;
}
</style>
