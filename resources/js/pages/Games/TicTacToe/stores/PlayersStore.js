// stores/counter.js
import { defineStore } from "pinia";

export const usePlayersStore = defineStore("players", {
    state: () => {
        return { players: [] };
    },
    // could also be defined as
    // state: () => ({ count: 0 })
    actions: {
        setPlayers(players) {
            this.players = players;
        },
    },
});
