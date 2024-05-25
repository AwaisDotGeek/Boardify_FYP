<template>
    <footer>
        <div class="main">
            <div class="subscribe-option">
                <span class="sub-option-heading"
                    >Want us to email you ocasionally with Boardify news?</span
                >
                <form action="">
                    <div class="form-group">
                        <input
                            type="text"
                            placeholder="Enter your email address"
                        />
                        <input type="submit" value="Subscribe" />
                    </div>
                </form>
            </div>

            <div class="boardiy-intro-container">
                <h1 class="text-2xl mb-2">Boardify</h1>
                <p>
                    Players love our platform for online board games. Connect
                    with fellow gamers, sharpen your skills, and challenge
                    online opponents. Join us now to level up your gaming
                    experience!
                </p>
            </div>

            <div class="contacts">
                <a href="#" class="contact-link twitter-link">
                    <i class="fa-brands fa-twitter fa-2x contact-link-item"></i>
                </a>
                <a href="#" class="contact-link facebook-link">
                    <i
                        class="fa-brands fa-facebook fa-2x contact-link-item"
                    ></i>
                </a>
                <a href="#" class="contact-link linkedin-link">
                    <i
                        class="fa-brands fa-linkedin fa-2x contact-link-item"
                    ></i>
                </a>
            </div>

            <hr style="width: 90%; margin: 20px" />

            <div class="copyright-text-container">
                <p class="copyright-text">
                    © Boardify 2023. All rights reserved. Absolutely all of
                    them. <br />And yes, that includes you, Pal.
                </p>
            </div>
        </div>
    </footer>
</template>

<style scoped>
footer {
    background-color: #212b2a;
    padding: 5em 0 10px 0;

    display: grid;
    place-items: center;
}

.main {
    max-width: 700px;

    display: flex;
    flex-direction: column;
    align-items: center;
}

.subscribe-option {
    text-align: center;
}

.sub-option-heading {
    color: #fff;
    font-size: 36px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}

input {
    width: 75%;
    border-radius: 15px;
    padding: 15px;
    font-size: 16px;
    outline: none;
}

input[type="text"] {
    text-align: center;
}

input[type="submit"] {
    align-self: center;
    color: #fff;
    letter-spacing: 0.3px;
    background: linear-gradient(-90deg, #0e0f11, #404a4c);
}

input[type="submit"]:hover {
    background: linear-gradient(90deg, #0e0f11, #404a4c);
    transform: none;
    box-shadow: none;
    cursor: pointer;
}

.boardiy-intro-container {
    margin: 20px 0;
    max-width: 500px;

    display: flex;
    flex-direction: column;
    gap: 10px;
}

h1 {
    color: #fff;
}

p {
    text-align: center;
    color: #fff;
    letter-spacing: 0.3px;
}

.contacts {
    margin: 15px;
    display: flex;
    gap: 15px;
}

.contact-link {
    height: 72px;
    width: 72px;
    display: grid;
    place-items: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 0 3px 0 #fff;
    transition: all 0.3s ease-in-out;
}

.contact-link-item {
    color: #404a4c;
    transition: all 0.3s ease-in-out;
    border-radius: 50%;
}

.contact-link:hover {
    box-shadow: 0 0 8px 0 #fff;
}
.twitter-link:hover .fa-twitter {
    color: #0077b5;
}

.facebook-link:hover .fa-facebook {
    color: #17a6fc;
}

.linkedin-link:hover .fa-linkedin {
    color: #0077b5;
}

.copyright-text-container {
    max-width: 500px;
}

.copyright-text {
    color: #ccc;
}
</style>
