/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "black-1": "#404a4c",
                "black-2": "#3a434a",
                "black-3": "#333738",
                "black-4": "#212b2a",
                "black-5": "#0e0F11",
            },
            boxShadow: {
                bubble:
                    "inset 0.5em 0.5em 0.5em rgba(255, 255, 255, 0.5),\n" +
                    "            inset -0.5em -0.5em 0.5em rgba(0, 0, 0, 0.35),\n" +
                    "            0.1em 0.1em 0.5em black;",
            },
            height: {
                50: "50px",
                height_5: "5vh",
                height_80: "80vh",
                height_85: "85vh",
                height_90: "90vh",
                height_100: "100vh",
            },
            width: {
                width_90: "90%",
            },
        },
    },
    plugins: [],
};
