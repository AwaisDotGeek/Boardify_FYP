<template>
    <div
        class="square"
        :class="{
            'rotate-180': loadFor === 'black',
        }"
        :style="{
            background: getColor(
                this.squareProps.coords.row,
                this.squareProps.coords.col,
            ),
        }"
        @click="handleSquareClick"
    >
        <Piece
            v-if="squareProps.square.piece"
            :imgSrc="squareProps.square.piece.imgSrc"
            :alt="squareProps.square.piece.imgAlt"
        />

        <!-- Highlight selected square / Also used to show previous move -->
        <div
            :class="{
                hidden: !squareProps.square.highlighted,
                'absolute flex justify-center items-center inset-0 z-[1]':
                    squareProps.square.highlighted,
            }"
        >
            <div
                :class="{
                    'bg-orange-300 w-[85%] h-[85%]': squareProps.square.piece,
                    'bg-orange-400 w-[50%] h-[50%]': !squareProps.square.piece,
                }"
                class="rounded-full"
            ></div>
        </div>

        <!--   Highlighted background for showing move     -->
        <div
            :class="{
                hidden: !squareProps.square.highlightedForMove,
                'opacity-70 w-[35%] h-[35%] rounded-full':
                    squareProps.square.highlightedForMove &&
                    !squareProps.square.piece,
                'w-[60%] h-[60%]': squareProps.square.highlighted,
            }"
            :style="{
                background: getColorToShowMove(
                    this.squareProps.coords.row,
                    this.squareProps.coords.col,
                ),
            }"
        ></div>

        <!-- Highlighted for capture -->
        <div
            :class="{
                hidden:
                    !squareProps.square.piece &&
                    squareProps.square.highlightedForMove,
                'absolute inset-0 rounded-full':
                    squareProps.square.piece &&
                    squareProps.square.highlightedForMove,
            }"
            :style="{
                boxShadow: `inset 0 0 0.8rem ${getColorToShowMove(
                    this.squareProps.coords.row,
                    this.squareProps.coords.col,
                )}`,
            }"
        ></div>

        <!-- Indexers for White player -->
        <span
            v-if="loadFor === 'white' && squareProps.coords.col === 0"
            class="squareIndex numericIndex"
            :style="{
                color: getIndexerColor(),
            }"
        >
            {{ this.squareProps.numericIndexer }}
        </span>
        <span
            v-if="loadFor === 'white' && squareProps.coords.row === 7"
            class="squareIndex alphaIndex"
            :style="{
                color: getIndexerColor(),
            }"
        >
            {{ this.squareProps.alphaIndexer }}
        </span>

        <!-- Indexers for Black player -->
        <span
            v-if="loadFor === 'black' && squareProps.coords.col === 7"
            class="squareIndex numericIndex"
            :style="{
                color: getIndexerColor(),
            }"
        >
            {{ this.squareProps.numericIndexer }}
        </span>
        <span
            v-if="loadFor === 'black' && squareProps.coords.row === 0"
            class="squareIndex alphaIndex"
            :style="{
                color: getIndexerColor(),
            }"
        >
            {{ this.squareProps.alphaIndexer }}
        </span>

        <!-- For king in danger -->
        <div
            :class="{
                    'hidden': !squareProps.square.highlightedForDanger,
                    'bg-red-500 opacity-80': squareProps.square.highlightedForDanger && squareProps.square.piece && squareProps.square.piece.name.endsWith('K'),
                }"
            class="absolute w-[85%] h-[85%] rounded-full"
        ></div>
    </div>
</template>

<script>
import Piece from "./Piece.vue";
export default {
    props: ["squareProps", "loadFor"],
    data() {
        return {
            color: "",
        };
    },

    // created() {
    //     if(this.squareProps.square.piece) {
    //         if (this.squareProps.square.piece.name === 'bR') {
    //             console.log(this.squareProps.square);
    //         }
    //     }
    // },

    components: {
        Piece,
    },
    methods: {
        handleSquareClick() {
            this.$emit("selectPiece", {
                selectedPiece: {
                    alphaIndexer: this.squareProps.alphaIndexer,
                    numericIndexer: this.squareProps.numericIndexer,
                    coords: this.squareProps.coords,
                    square: this.squareProps.square, // square has the piece
                },
            });
        },

        // helps provide the appropriate color to a square
        getColor(row, col) {
            let color = this.squareProps.colorMap[`${row},${col}`];
            this.color = color;
            return color;
        },

        // For squares with pieces / to show possible capture
        getColorToShowMove(row, col) {
            let color = null;
            if (col > 0 && row > 0 && col < 7 && row < 7) {
                color = this.squareProps.colorMap[`${row},${col + 1}`];
            } else {
                color =
                    this.squareProps.colorMap[
                        `${row === 0 ? row + 1 : row - 1},${col}`
                    ];
            }
            return color;
        },

        // color = backgroundColor
        getIndexerColor() {
            if (this.color === this.squareProps.colorPair.light) {
                return this.squareProps.colorPair.dark;
            } else {
                return this.squareProps.colorPair.light;
            }
        },
    },
};
</script>

<style>
.square {
    height: 60px;
    width: 60px;
    display: grid;
    place-items: center;

    position: relative;
}

.squareIndex {
    position: absolute;
    font-size: 12px;
    color: #fff;
}

.numericIndex {
    top: 0;
    left: 0;
    margin-top: 1px;
    margin-left: 1px;
}

.alphaIndex {
    right: 0;
    bottom: 0;
    margin-right: 2px;
    margin-bottom: 1px;
}

.highlighted {
    box-shadow:
        inset 0 0 5px 2px rosybrown,
        inset 0 0 5px 2px rosybrown;
}
</style>
