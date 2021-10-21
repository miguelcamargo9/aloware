<template>
    <div>
        <Comment
            :getCommentsParent="getComments"
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
        />
    </div>
</template>

<script>
import Comment from "./CommentComponent.vue";

export default {
    name: "ListComments",
    components: {
        Comment
    },
    data() {
        return {
            comments: {}
        };
    },
    methods: {
        getComments() {
            axios.get("/comments").then(response => {
                this.comments = response.data;
            });
        }
    },
    created() {
        this.getComments();
    }
};
</script>
