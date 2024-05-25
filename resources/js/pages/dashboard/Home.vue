<template>
    <!-- Nav here -->
    <div class="main">
        <header>
            <Navbar
                @toggleUserProfileSlider="toggleUserProfileSlider">
            </Navbar>
        </header>

        <div class="flex flex-col justify-center items-center gap-5 p-5">
            <GameCard id="TTT" gameName="Tic Tac Toe" gameHeadText="Embrace the Battle of Wits!">
                <template v-slot:gameText>
                    Greetings, fellow strategists! I am Tic Tac Toe,
                    the timeless arena where intellects collide in a
                    thrilling battle of Xs and Os. Engage in a strategic
                    showdown, where every move holds the key to victory.
                    Whether you're a seasoned pro or a newcomer,
                    I offer an exciting journey through the world of
                    tactical thinking and decisive plays. Unleash your
                    inner strategist, anticipate your opponent's moves,
                    and emerge triumphant in this classic duel of mind and
                    logic. Join me now, and let's embark on an epic quest
                    to conquer the nine-space battlefield with your wits
                    and cunning! ⭕❌🧠
                </template>
            </GameCard>

            <GameCard id="chess" gameName="Chess" gameHeadText="Unleash Your Strategic Brilliance!">
                <template v-slot:gameText>
                    Greetings, noble players! I am Chess, the timeless
                    battleground where minds clash in a symphony of strategy
                    and tactics. Engage in a dance of cunning moves, daring
                    sacrifices, and triumphant checkmates. Whether you're a
                    grandmaster or a novice, I offer an exhilarating journey
                    through centuries of rich tradition and intellectual
                    prowess. Unveil your inner tactician, predict your opponent's
                    every step, and emerge victorious in this regal duel of wits.
                    Join me, and let's embark on an epic quest to conquer the checkered
                    realm with your strategic might! 🏰♟️👑
                </template>
            </GameCard>

            <GameCard id="ludo" gameName="Ludo" gameHeadText="Roll the Dice, Create Memories, and Seize Victory!">
                <template v-slot:gameText>
                    Greetings, adventurers of all ages! I am Ludo,
                    the timeless classic that brings joy, camaraderie,
                    and a dash of luck to your gaming gatherings.
                    Embark on a colorful journey as you navigate your
                    tokens across the board, evading pitfalls and
                    forging alliances. With every roll of the dice,
                    friendships are strengthened, laughter echoes
                    through the air, and thrilling comebacks await.
                    Whether you're strategizing for supremacy or
                    savoring the thrill of chance, Ludo promises a
                    rollercoaster of emotions and unforgettable moments.
                    Join me, and let's embark on an exhilarating ride of fun,
                    competition, and cherished memories that last a lifetime! 🎲🌈🏆
                </template>
            </GameCard>

            <GameCard id="rang" gameName="Rang" gameHeadText="Experience the Colors of Strategy and Skill!">
                <template v-slot:gameText>
                    Greetings, strategic thinkers and card game enthusiasts! I am Rang,
                    a captivating card game that combines intellect, foresight, and
                    quick decision-making. Engage in a battle of wits as you aim to
                    capture tricks and outmaneuver your opponents. Every card played
                    is a brushstroke of strategy, every move a stroke of mastery.
                    With each round, the canvas of possibilities expands, creating
                    intricate patterns of gameplay that challenge your skills.
                    Whether you're devising a winning strategy or embracing the thrill
                    of unpredictability, Rang promises a vibrant tapestry of
                    challenge and excitement. Join me, and let's immerse ourselves
                    in a world where every card has a story, and every play a stroke
                    of brilliance. 🃏🎨🏆
                </template>
            </GameCard>

            <GameCard id="snakes-and-ladders" gameName="Snakes and Ladders" gameHeadText="Climb, Slide, and Chase Victory's Heights!">
                <template v-slot:gameText>
                    Greetings, adventurers and seekers of ups and downs! I am Snakes and Ladders,
                    the thrilling journey that propels you through twists and turns, surprises
                    and setbacks. Ascend the ladder of luck and descend the snake of chance as
                    you navigate a board of squares and serpents. Every roll of the dice holds
                    the promise of progress or the plunge of a snake's tail. Strategy and fortune
                    dance in harmony as you strive to reach the zenith of victory.
                    With every roll, friendships are forged, and rivalries ignited, making each
                    play a memorable saga of shared experiences. Whether you're seeking to conquer
                    your rivals or reveling in the unpredictable ride, Snakes and Ladders promises
                    an exhilarating ride of anticipation and elation. Join me, and let's embark on
                    a voyage of chutes and ladders, where the destination is uncertain, but the
                    thrill is guaranteed! 🎲🐍🪜
                </template>
            </GameCard>
        </div>

        <UserProfileSlider :isUserProfileCardActive="isUserProfileCardActive"></UserProfileSlider>

        <Footer></Footer>
    </div>

</template>

<script>
import Footer from "../../shared/Footer.vue";
import GameCard from "../../shared/GameCard.vue";
import Navbar from '../../shared/Navbar.vue';
import UserProfileSlider from "../../shared/UserProfileSlider.vue";
export default {
    data() {
        return {
            isUserProfileCardActive: false,
            userId: null,
        }
    },
    components: {
        Footer,
        GameCard,
        Navbar,
        UserProfileSlider,
    },
    methods: {
        toggleUserProfileSlider() {
            this.isUserProfileCardActive = !this.isUserProfileCardActive;
        },
    }
}
</script>

