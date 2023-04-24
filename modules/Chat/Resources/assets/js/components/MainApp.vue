<template>
    <div>
        <chat-app></chat-app>
        <notifications group="foo" />
    </div>
</template>

<script>
import ChatApp from "./ChatApp";
export default {
    components: {
        ChatApp
    },
    data() {
        return {};
    }
};
</script>

<style scoped></style>
