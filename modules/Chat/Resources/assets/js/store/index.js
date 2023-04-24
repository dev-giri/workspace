import Axios from "axios";

// Steps: Actions -> State -> Mutations  -> Getters

export default {
    state: {
        users: [],
        messages: []
    },
    mutations: {
        userList(state, payload) {
            return (state.users = payload);
        },
        userMessage(state, payload) {
            return (state.messages = payload);
        }
    },
    actions: {
        userList(context) {
            Axios.get("/chat/user-list").then(response => {
                context.commit("userList", response.data);
            });
        },
        userMessage(context, userId) {
            Axios.get("/chat/user-message/" + userId).then(response => {
                context.commit("userMessage", response.data);
            });
        }
    },
    getters: {
        userList(state) {
            return state.users;
        },
        userMessage(state) {
            return state.messages;
        }
    }
};
