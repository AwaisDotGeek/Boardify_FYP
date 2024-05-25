<template>
    <div class="board relative w-fit" :class="{ 'rotate-180': loadFor === 'black' }">
        <div class="row" v-for="(row, rowIndex) in boardState" :key="rowIndex">
            <div v-for="(square, colIndex) in row" :key="colIndex">
                <Square
                    :squareProps="getSquareProps(rowIndex, colIndex, square)"
                    :loadFor="loadFor"
                    @selectPiece="handleSelectPiece"
                />
            </div>
        </div>

        <Promote
            :color="playerToMove"
            :showPromotionOption="showPromotionOption"
            @promoteTo="handlePawnPromotion"
        />
    </div>
</template>

<script>
import Square from "./Square.vue";
import Promote from "./Promote.vue";

export default {
    props: [
        "playerToMove",
        "boardState",
        "colorPair",
        "loadFor",
        "showPromotionOption",
    ],
    data() {
        return {
            colorMap: {},
            selectedPiece: "",
        };
    },
    components: {
        Promote,
        Square,
    },
    mounted() {
        this.calculateColors();
    },
    methods: {
        getSquareProps(row, col, square) {
            const alphaIndexer = this.associateAlphaIndexer(col);
            const numericIndexer = this.associateNumericIndexer(row);

            const squareProps = {
                alphaIndexer,
                numericIndexer,
                coords: { row, col },
                colorPair: this.colorPair,
                colorMap: this.colorMap,
                square,
            };

            // Add piece information only if a piece exists:
            if (square.piece != null) {
                squareProps.square.piece.name = square.piece.name;
                squareProps.square.piece.imgSrc = this.getImgSrc(
                    square.piece.name,
                );
                squareProps.square.piece.imgAlt = square.piece.name;
            }
            return squareProps;
        },

        associateNumericIndexer(row) {
            return (row - 8) * -1;
        },

        associateAlphaIndexer(col) {
            return this.getAlpha(col);
        },

        getAlpha(col) {
            switch (col) {
                case 0:
                    return "a";
                case 1:
                    return "b";
                case 2:
                    return "c";
                case 3:
                    return "d";
                case 4:
                    return "e";
                case 5:
                    return "f";
                case 6:
                    return "g";
                case 7:
                    return "h";
            }
        },

        // get image source
        getImgSrc(name) {
            switch (name) {
                case "bB":
                    return "../assets/chess_pieces/bB.svg";
                case "bK":
                    return "../assets/chess_pieces/bK.svg";
                case "bN":
                    return "../assets/chess_pieces/bN.svg";
                case "bP":
                    return "../assets/chess_pieces/bP.svg";
                case "bQ":
                    return "../assets/chess_pieces/bQ.svg";
                case "bR":
                    return "../assets/chess_pieces/bR.svg";
                case "wB":
                    return "../assets/chess_pieces/wB.svg";
                case "wK":
                    return "../assets/chess_pieces/wK.svg";
                case "wN":
                    return "../assets/chess_pieces/wN.svg";
                case "wP":
                    return "../assets/chess_pieces/wP.svg";
                case "wQ":
                    return "../assets/chess_pieces/wQ.svg";
                case "wR":
                    return "../assets/chess_pieces/wR.svg";
            }
        },

        // this is called from the mounted hook once after app is loaded
        calculateColors() {
            for (let row = 0; row < 8; row++) {
                for (let col = 0; col < 8; col++) {
                    this.colorMap[`${row},${col}`] = this.isRowColEven(row, col) ||
                    this.isRowColOdd(row, col)
                        ? this.colorPair.light
                        : this.colorPair.dark;
                }
            }
        },

        isRowColEven(row, col) {
            return row % 2 === 0 && col % 2 === 0;
        },
        isRowColOdd(row, col) {
            return row % 2 !== 0 && col % 2 !== 0;
        },

        handleSelectPiece(props) {
            this.$emit("selectPiece", props);
        },

        handlePawnPromotion(args) {
            this.$emit("promotePawnTo", args);
        },
    },
};
</script>

<style>
.row {
    display: flex;
}
</style>
