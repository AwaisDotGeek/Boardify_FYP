let isBlackKingInDanger = false;
let isWhiteKingInDanger = false;
let blackKingCoords = null;
let whiteKingCoords = null;
let isKingSelected = false;

// Get img source provided the piece name
export const getImgSrc = (name) => {
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
};

const simulateMove = (currentBoardState, move) => {
    // Create a deep copy of the current board state to avoid modifying the original state
    const newBoardState = JSON.parse(JSON.stringify(currentBoardState));

    // Extract information about the move
    const { from, to } = move;

    // Retrieve the piece being moved
    const pieceToMove = newBoardState[from.row][from.col].piece;

    // Remove the piece from its original position
    newBoardState[from.row][from.col].piece = null;

    // Place the piece at its new position
    newBoardState[to.row][to.col].piece = pieceToMove;

    // Return the new board state reflecting the hypothetical move
    return newBoardState;
};

// Calculate valid moves
// isSelfKingInDanger for white piece means white king and vice versa
export const calculateValidMoves = (boardState, forSquare, isSelfKingInDanger, kingCoords) => {
    const piece = forSquare.square.piece;
    const coords = forSquare.coords;

    isKingSelected = piece.name.endsWith('K');

    if (isSelfKingInDanger) {
        if (piece.name.startsWith('b')) {
            isBlackKingInDanger = true;
            blackKingCoords = kingCoords;
        } else {
            isWhiteKingInDanger = true;
            whiteKingCoords = kingCoords;
        }
    } else {
        isBlackKingInDanger = false;
        isWhiteKingInDanger = false;
    }

    return callAppropriateMethodToGetValidMoves(boardState, coords, piece.name);
};

const _calculateValidMoves = (boardState, forSquare) => {
    const piece = forSquare.square.piece;
    const coords = forSquare.coords;

    return callAppropriateMethodToGetValidMoves(boardState, coords, piece.name);
}

const callAppropriateMethodToGetValidMoves = (boardState, coords, pieceName) => {
    switch (pieceName) {
        case "bK":
            return calculateValidMovesForBlackKing(
                boardState,
                coords.row,
                coords.col,
            );
        case "wK":
            return calculateValidMovesForWhiteKing(
                boardState,
                coords.row,
                coords.col,
            );
        case "bQ":
            return calculateValidMovesForBlackQueen(
                boardState,
                coords.row,
                coords.col,
            );
        case "wQ":
            return calculateValidMovesForWhiteQueen(
                boardState,
                coords.row,
                coords.col,
            );
        case "bP":
            return calculateValidMovesForBlackPawn(
                boardState,
                coords.row,
                coords.col,
            );
        case "wP":
            return calculateValidMovesForWhitePawn(
                boardState,
                coords.row,
                coords.col,
            );
        case "bR":
            return calculateValidMovesForBlackRook(
                boardState,
                coords.row,
                coords.col,
            );
        case "wR":
            return calculateValidMovesForWhiteRook(
                boardState,
                coords.row,
                coords.col,
            );
        case "bB":
            return calculateValidMovesForBlackBishop(
                boardState,
                coords.row,
                coords.col,
            );
        case "wB":
            return calculateValidMovesForWhiteBishop(
                boardState,
                coords.row,
                coords.col,
            );
        case "bN":
            return calculateValidMovesForBlackKnight(
                boardState,
                coords.row,
                coords.col,
            );
        case "wN":
            return calculateValidMovesForWhiteKnight(
                boardState,
                coords.row,
                coords.col,
            );
    }
}

const isOpponentPiece = (piece, currentPlayer) => {
    return piece && piece.name.charAt(0) !== currentPlayer;
};

const isValidSquare = (row, col) => {
    return row >= 0 && row < 8 && col >= 0 && col < 8;
};

const canCaptureOrMoveToEmptySquare = (boardState, row, col, currentPlayer) => {
    return (
        isValidSquare(row, col) &&
        (!boardState[row][col].piece ||
            (boardState[row][col].piece.name.startsWith(
                currentPlayer === "w" ? "b" : "w",
            )) // For king = /* && boardState[row][col].piece.name[1] !== "K" */
    ))
};

// Is King in danger ?
export const isKingInDanger = (boardState, kingCoords, currentPlayer) => {
    const opponent = currentPlayer === 'w' ? 'b' : 'w';

    if (!kingCoords) {
        return false;
    }

    // Iterate through all squares on the board
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            const square = boardState[row][col];
            // Check if the square contains an opponent's piece
            if (square.piece && square.piece.name.startsWith(opponent)) {
                // Calculate valid moves for the opponent's piece
                const validMoves = _calculateValidMoves(boardState, { square, coords: { row, col } });
                // Check if any valid move includes the king's square
                for (const move of validMoves) {
                    if (move.row === kingCoords.row && move.col === kingCoords.col) {
                        return true; // King is in danger
                    }
                }
            }
        }
    }

    return false; // King is not in danger
};

const filterMoves = (boardState, row, col, kingCoords, currentPlayer, validMoves) => {
    // Simulate moves only if the black king is in danger
    for (const move of validMoves) {
        // Simulate the move
        const newBoardState = simulateMove(boardState, { from: { row, col }, to: { row: move.row, col: move.col } });
        // Check if the move removes danger from the black king
        if (!isKingInDanger(newBoardState, kingCoords, currentPlayer)) {
            move.isSafe = true; // Mark the move as safe
        }
    }

    // Filter out moves that are not safe for the black king
    return validMoves.filter(move => move.isSafe);
}

// Is Game Over ?
// Check if the game is over (checkmate or stalemate)
// Check if the game is over (checkmate or stalemate)
export const isGameOver = (boardState, kingCoords, currentPlayer) => {
    // Check if the king is in danger
    if (isKingInDanger(boardState, kingCoords, currentPlayer)) {

        if (currentPlayer === 'w') {
            isWhiteKingInDanger = true;
        } else {
            isBlackKingInDanger = true;
        }

        // Check if the king can move to safety
        const validMoves = _calculateValidMoves(boardState, { square: boardState[kingCoords.row][kingCoords.col], coords: { row: kingCoords.row, col: kingCoords.col } });

        // If the king can move to safety, it's not checkmate
        if (validMoves.length > 0) {
            isWhiteKingInDanger = false;
            isBlackKingInDanger = false;
            return false; // Game continues
        }

        // If the king cannot move to safety, check if any other piece can protect the king or capture the threatening piece
        for (let row = 0; row < 8; row++) {
            for (let col = 0; col < 8; col++) {
                const square = boardState[row][col];
                // Check if the square contains a piece of the current player
                if (square.piece && square.piece.name.startsWith(currentPlayer)) {
                    // Calculate valid moves for the piece
                    const validMoves = _calculateValidMoves(boardState, { square, coords: { row, col } });
                    // If the piece can capture the threatening piece or move to block the threat, it's not checkmate
                    for (const move of validMoves) {
                        if (move.row === kingCoords.row && move.col === kingCoords.col) {
                            const newBoardState = simulateMove(boardState, { from: { row, col }, to: { row: kingCoords.row, col: kingCoords.col } });

                            if (!isKingInDanger(newBoardState, kingCoords, currentPlayer)) {
                                isWhiteKingInDanger = false;
                                isBlackKingInDanger = false;
                                return false;
                            }
                        }
                    }
                }
            }
        }

        // If no piece can protect the king or capture the threatening piece, it's checkmate
        return true; // Checkmate
    }

    // If the king is not in danger, it's not checkmate
    return false; // Game continues
};

// <============================================== White Pieces =====================================================>
// Calculate valid moves for White King
const calculateValidMovesForWhiteKing = (boardState, row, col) => {
    let validMoves = [];

    // Check moves in all directions (including diagonals)
    for (let i = row - 1; i <= row + 1; i++) {
        for (let j = col - 1; j <= col + 1; j++) {
            if (
                i >= 0 &&
                i < 8 &&
                j >= 0 &&
                j < 8 &&
                !(i === row && j === col)
            ) {
                if (
                    !boardState[i][j].piece ||
                    isOpponentPiece(boardState[i][j].piece, "w")
                ) {
                    validMoves.push({ row: i, col: j });
                }
            }
        }
    }

    // Add logic for castling if needed

    // Check if white king goes in danger
    if (isWhiteKingInDanger || isKingSelected) {
        isKingSelected = false;
        for (const move of validMoves) {
            // Simulate the move
            const newBoardState = simulateMove(boardState, { from: { row, col }, to: { row: move.row, col: move.col } });
            const newKingCoords = { row: move.row, col: move.col }
            // Check if the move removes danger from the black king
            if (!isKingInDanger(newBoardState, newKingCoords, 'w')) {
                move.isSafe = true; // Mark the move as safe
            }
        }
    }

    // Filter out moves that are not safe for the black king
    return validMoves.filter(move => move.isSafe);
};

// Calculate valid moves for White Queen
const calculateValidMovesForWhiteQueen = (boardState, row, col) => {
    const validMoves = [];

    // Rook-like moves
    const rookMoves = calculateValidMovesForWhiteRook(boardState, row, col);
    validMoves.push(...rookMoves);

    // Bishop-like moves
    const bishopMoves = calculateValidMovesForWhiteBishop(boardState, row, col);
    validMoves.push(...bishopMoves);

    return validMoves;
};

// Calculate valid moves for White Pawn
const calculateValidMovesForWhitePawn = (boardState, row, col) => {
    let validMoves = [];

    // Pawn can move one step forward
    if (row - 1 >= 0 && !boardState[row - 1][col].piece) {
        validMoves.push({ row: row - 1, col });
    }

    // Pawn can capture diagonally to the left
    if (row - 1 >= 0 && col - 1 >= 0 && boardState[row - 1][col - 1].piece) {
        const capturedPiece = boardState[row - 1][col - 1].piece;
        if (
            capturedPiece.name.charAt(0) !== "w"
        ) {
            validMoves.push({ row: row - 1, col: col - 1 });
        }
    }

    // Pawn can capture diagonally to the right
    if (row - 1 >= 0 && col + 1 < 8 && boardState[row - 1][col + 1].piece) {
        const capturedPiece = boardState[row - 1][col + 1].piece;
        if (
            capturedPiece.name.charAt(0) !== "w"
        ) {
            validMoves.push({ row: row - 1, col: col + 1 });
        }
    }

    // Add logic for double move from starting position
    if (
        row === 6 &&
        !boardState[row - 1][col].piece &&
        !boardState[row - 2][col].piece
    ) {
        validMoves.push({ row: row - 2, col });
    }

    // Add logic for en passant capture

    // Add logic for promotion
    if (row === 1) {
        // If the pawn has reached the opposite end of the board
        // Check if the same column is empty
        if (!boardState[0][col].piece) {
            // cannot go straight
            // Add promotion moves only if the same column is empty
            validMoves.push("promotion");
        } else {
            validMoves.forEach((move) => {
                if (boardState[move.row][move.col].piece) {
                    validMoves.push("promotion");
                }
            });
        }
    }

    // Check if the White king is in danger
    if (isWhiteKingInDanger) {
        validMoves = filterMoves(boardState, row, col, whiteKingCoords, 'w', validMoves);
    }

    return validMoves;
};

// Calculate valid moves for White Rook
const calculateValidMovesForWhiteRook = (boardState, row, col) => {
    let validMoves = [];

    // Check moves in the upward direction
    for (let i = row - 1; i >= 0; i--) {
        if (boardState[i][col].piece) {
            if (isOpponentPiece(boardState[i][col].piece, "w")) {
                validMoves.push({ row: i, col });
            }
            break;
        } else {
            validMoves.push({ row: i, col });
        }
    }

    // Check moves in the downward direction
    for (let i = row + 1; i < 8; i++) {
        if (boardState[i][col].piece) {
            if (isOpponentPiece(boardState[i][col].piece, "w")) {
                validMoves.push({ row: i, col });
            }
            break;
        } else {
            validMoves.push({ row: i, col });
        }
    }

    // Check moves to the left
    for (let j = col - 1; j >= 0; j--) {
        if (boardState[row][j].piece) {
            if (isOpponentPiece(boardState[row][j].piece, "w")) {
                validMoves.push({ row, col: j });
            }
            break;
        } else {
            validMoves.push({ row, col: j });
        }
    }

    // Check moves to the right
    for (let j = col + 1; j < 8; j++) {
        if (boardState[row][j].piece) {
            if (isOpponentPiece(boardState[row][j].piece, "w")) {
                validMoves.push({ row, col: j });
            }
            break;
        } else {
            validMoves.push({ row, col: j });
        }
    }

    // Check if the White king is in danger
    if (isWhiteKingInDanger) {
        validMoves = filterMoves(boardState, row, col, whiteKingCoords, 'w', validMoves);
    }

    return validMoves;
};

const calculateValidMovesForWhiteBishop = (boardState, row, col) => {
    let validMoves = [];

    // Top-left diagonal
    for (let i = 1; row - i >= 0 && col - i >= 0; i++) {
        if (!boardState[row - i][col - i].piece) {
            validMoves.push({ row: row - i, col: col - i });
        } else {
            if (boardState[row - i][col - i].piece.name.startsWith("b")) {
                validMoves.push({ row: row - i, col: col - i });
            }
            break;
        }
    }

    // Top-right diagonal
    for (let i = 1; row - i >= 0 && col + i < 8; i++) {
        if (!boardState[row - i][col + i].piece) {
            validMoves.push({ row: row - i, col: col + i });
        } else {
            if (boardState[row - i][col + i].piece.name.startsWith("b")) {
                validMoves.push({ row: row - i, col: col + i });
            }
            break;
        }
    }

    // Bottom-left diagonal
    for (let i = 1; row + i < 8 && col - i >= 0; i++) {
        if (!boardState[row + i][col - i].piece) {
            validMoves.push({ row: row + i, col: col - i });
        } else {
            if (boardState[row + i][col - i].piece.name.startsWith("b")) {
                validMoves.push({ row: row + i, col: col - i });
            }
            break;
        }
    }

    // Bottom-right diagonal
    for (let i = 1; row + i < 8 && col + i < 8; i++) {
        if (!boardState[row + i][col + i].piece) {
            validMoves.push({ row: row + i, col: col + i });
        } else {
            if (boardState[row + i][col + i].piece.name.startsWith("b")) {
                validMoves.push({ row: row + i, col: col + i });
            }
            break;
        }
    }

    // Check if the White king is in danger
    if (isWhiteKingInDanger) {
        validMoves = filterMoves(boardState, row, col, whiteKingCoords, 'w', validMoves);
    }

    return validMoves;
};

const calculateValidMovesForWhiteKnight = (boardState, row, col) => {
    let validMoves = [];

    const knightMoves = [
        { row: row - 2, col: col - 1 },
        { row: row - 2, col: col + 1 },
        { row: row - 1, col: col - 2 },
        { row: row - 1, col: col + 2 },
        { row: row + 1, col: col - 2 },
        { row: row + 1, col: col + 2 },
        { row: row + 2, col: col - 1 },
        { row: row + 2, col: col + 1 },
    ];

    for (const move of knightMoves) {
        if (
            isValidSquare(move.row, move.col) &&
            canCaptureOrMoveToEmptySquare(boardState, move.row, move.col, "w")
        ) {
            validMoves.push(move);
        }
    }

    // Check if the White king is in danger
    if (isWhiteKingInDanger) {
        validMoves = filterMoves(boardState, row, col, whiteKingCoords, 'w', validMoves);
    }

    return validMoves;
};


// <============================================== Black Pieces =====================================================>
// Calculate valid moves for Black King
const calculateValidMovesForBlackKing = (boardState, row, col) => {
    let validMoves = [];

    // Check moves in all directions (including diagonals)
    for (let i = row - 1; i <= row + 1; i++) {
        for (let j = col - 1; j <= col + 1; j++) {
            if (
                i >= 0 &&
                i < 8 &&
                j >= 0 &&
                j < 8 &&
                !(i === row && j === col)
            ) {
                if (
                    !boardState[i][j].piece ||
                    isOpponentPiece(boardState[i][j].piece, "b")
                ) {
                    validMoves.push({ row: i, col: j });
                }
            }
        }
    }

    // Add logic for castling if needed

    // Check if black king goes in danger
    if (isBlackKingInDanger || isKingSelected) {
        for (const move of validMoves) {
            // Simulate the move
            const newBoardState = simulateMove(boardState, { from: { row, col }, to: { row: move.row, col: move.col } });
            const newKingCoords = { row: move.row, col: move.col }
            // Check if the move removes danger from the black king
            if (!isKingInDanger(newBoardState, newKingCoords, 'b')) {
                move.isSafe = true; // Mark the move as safe
            }
        }
    }

    // Filter out moves that are not safe for the black king
    return validMoves.filter(move => move.isSafe);
};

// Calculate valid moves for Black Queen
const calculateValidMovesForBlackQueen = (boardState, row, col) => {
    const validMoves = [];

    // Rook-like moves
    const rookMoves = calculateValidMovesForBlackRook(boardState, row, col);
    validMoves.push(...rookMoves);

    // Bishop-like moves
    const bishopMoves = calculateValidMovesForBlackBishop(boardState, row, col);
    validMoves.push(...bishopMoves);

    // If the black king is not in danger, return all valid moves without simulating
    return validMoves;
}

const calculateValidMovesForBlackBishop = (boardState, row, col) => {
    let validMoves = [];

    // Top-left diagonal
    for (let i = 1; row - i >= 0 && col - i >= 0; i++) {
        if (!boardState[row - i][col - i].piece) {
            validMoves.push({ row: row - i, col: col - i });
        } else {
            if (boardState[row - i][col - i].piece.name.startsWith("w")) {
                validMoves.push({ row: row - i, col: col - i });
            }
            break;
        }
    }

    // Top-right diagonal
    for (let i = 1; row - i >= 0 && col + i < 8; i++) {
        if (!boardState[row - i][col + i].piece) {
            validMoves.push({ row: row - i, col: col + i });
        } else {
            if (boardState[row - i][col + i].piece.name.startsWith("w")) {
                validMoves.push({ row: row - i, col: col + i });
            }
            break;
        }
    }

    // Bottom-left diagonal
    for (let i = 1; row + i < 8 && col - i >= 0; i++) {
        if (!boardState[row + i][col - i].piece) {
            validMoves.push({ row: row + i, col: col - i });
        } else {
            if (boardState[row + i][col - i].piece.name.startsWith("w")) {
                validMoves.push({ row: row + i, col: col - i });
            }
            break;
        }
    }

    // Bottom-right diagonal
    for (let i = 1; row + i < 8 && col + i < 8; i++) {
        if (!boardState[row + i][col + i].piece) {
            validMoves.push({ row: row + i, col: col + i });
        } else {
            if (boardState[row + i][col + i].piece.name.startsWith("w")) {
                validMoves.push({ row: row + i, col: col + i });
            }
            break;
        }
    }

    // Check if the black king is in danger
    if (isBlackKingInDanger) {
        validMoves = filterMoves(boardState, row, col, blackKingCoords, 'b', validMoves);
    }

    return validMoves;
};

const calculateValidMovesForBlackKnight = (boardState, row, col) => {
    let validMoves = [];

    const knightMoves = [
        { row: row - 2, col: col - 1 },
        { row: row - 2, col: col + 1 },
        { row: row - 1, col: col - 2 },
        { row: row - 1, col: col + 2 },
        { row: row + 1, col: col - 2 },
        { row: row + 1, col: col + 2 },
        { row: row + 2, col: col - 1 },
        { row: row + 2, col: col + 1 },
    ];

    for (const move of knightMoves) {
        if (
            isValidSquare(move.row, move.col) &&
            canCaptureOrMoveToEmptySquare(boardState, move.row, move.col, "b")
        ) {
            validMoves.push(move);
        }
    }

    // Check if the black king is in danger
    if (isBlackKingInDanger) {
        validMoves = filterMoves(boardState, row, col, blackKingCoords, 'b', validMoves);
    }

    return validMoves;
};

// Calculate valid moves for Black Rook
const calculateValidMovesForBlackRook = (boardState, row, col) => {
    let validMoves = [];

    // Check moves in the upward direction
    for (let i = row - 1; i >= 0; i--) {
        if (boardState[i][col].piece) {
            if (isOpponentPiece(boardState[i][col].piece, "b")) {
                validMoves.push({ row: i, col });
            }
            break;
        } else {
            validMoves.push({ row: i, col });
        }
    }

    // Check moves in the downward direction
    for (let i = row + 1; i < 8; i++) {
        if (boardState[i][col].piece) {
            if (isOpponentPiece(boardState[i][col].piece, "b")) {
                validMoves.push({ row: i, col });
            }
            break;
        } else {
            validMoves.push({ row: i, col });
        }
    }

    // Check moves to the left
    for (let j = col - 1; j >= 0; j--) {
        if (boardState[row][j].piece) {
            if (isOpponentPiece(boardState[row][j].piece, "b")) {
                validMoves.push({ row, col: j });
            }
            break;
        } else {
            validMoves.push({ row, col: j });
        }
    }

    // Check moves to the right
    for (let j = col + 1; j < 8; j++) {
        if (boardState[row][j].piece) {
            if (isOpponentPiece(boardState[row][j].piece, "b")) {
                validMoves.push({ row, col: j });
            }
            break;
        } else {
            validMoves.push({ row, col: j });
        }
    }

    // Check if the black king is in danger
    if (isBlackKingInDanger) {
        validMoves = filterMoves(boardState, row, col, blackKingCoords, 'b', validMoves);
    }

    return validMoves;
};

// Calculate valid moves for Black Pawn
const calculateValidMovesForBlackPawn = (boardState, row, col) => {
    let validMoves = [];

    // Pawn can move one step forward
    if (row + 1 < 8 && !boardState[row + 1][col].piece) {
        validMoves.push({ row: row + 1, col });
    }

    // Pawn can capture diagonally to the left
    if (row + 1 < 8 && col - 1 >= 0 && boardState[row + 1][col - 1].piece) {
        const capturedPiece = boardState[row + 1][col - 1].piece;
        if (capturedPiece.name.charAt(0) !== "b") {
            validMoves.push({ row: row + 1, col: col - 1 });
        }
    }

    // Pawn can capture diagonally to the right
    if (row + 1 < 8 && col + 1 < 8 && boardState[row + 1][col + 1].piece) {
        const capturedPiece = boardState[row + 1][col + 1].piece;
        if (capturedPiece.name.charAt(0) !== "b") {
            validMoves.push({ row: row + 1, col: col + 1 });
        }
    }

    // Add logic for double move from starting position
    if (
        row === 1 &&
        !boardState[row + 1][col].piece &&
        !boardState[row + 2][col].piece
    ) {
        validMoves.push({ row: row + 2, col });
    }

    // Add logic for en passant capture

    // Add logic for promotion
    if (row === 6) {
        // If the pawn has reached the opposite end of the board
        // Check if the same column is empty
        if (!boardState[7][col].piece) {
            // cannot go straight
            // Add promotion moves only if the same column is empty
            validMoves.push("promotion");
        } else {
            validMoves.forEach((move) => {
                if (boardState[move.row][move.col].piece) {
                    validMoves.push("promotion");
                }
            });
        }
    }

    // Check if the black king is in danger
    if (isBlackKingInDanger) {
        validMoves = filterMoves(boardState, row, col, blackKingCoords, 'b', validMoves);
    }

    return validMoves;
};
