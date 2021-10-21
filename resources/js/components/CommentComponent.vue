<template>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ comment.name }}</div>

                <div class="card-body">
                    <p>
                        {{ comment.comment }}
                    </p>
                    <button
                        class="btn btn-primary mb-2"
                        type="button"
                        data-bs-toggle="collapse"
                        :data-bs-target="'#collapse' + comment.id"
                        aria-expanded="false"
                        :aria-controls="'collapse' + comment.id"
                        v-on:click="
                            newComment.parent = comment.id;
                            getChildrenComments(comment.id);
                        "
                    >
                        Comment
                    </button>
                    <div class="collapse" :id="'collapse' + comment.id">
                        <div class="card card-body">
                            <form
                                method="POST"
                                v-on:submit.prevent="createComment"
                            >
                                <div class="mb-3">
                                    <label for="name" class="form-label"
                                        >Name</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        placeholder="Type the name here"
                                        v-model="newComment.name"
                                    />
                                    <span
                                        v-for="(error, key) in erros"
                                        :key="key"
                                        class="text-danger"
                                    >
                                        {{ error }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label"
                                        >Comment</label
                                    >
                                    <textarea
                                        placeholder="Type the comment here"
                                        class="form-control mb-2"
                                        id="comment"
                                        rows="3"
                                        v-model="newComment.comment"
                                    ></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    Save comment
                                </button>
                            </form>
                        </div>
                    </div>
                    <div v-if="hasChildren">
                        <Comment
                            v-for="child in children"
                            :key="child.id"
                            :comment="child"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Comment",
    props: {
        getCommentsParent: {
            type: Function
        },
        comment: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            erros: [],
            children: {},
            newComment: {
                name: "",
                comment: "",
                parent: null
            }
        };
    },
    methods: {
        getComments: function() {
            this.getCommentsParent();
        },
        getChildrenComments(commentId) {
            axios.get("/comments/" + commentId).then(response => {
                this.children = response.data;
            });
        },
        createComment() {
            axios
                .post("/comments/", {
                    name: this.newComment.name,
                    comment: this.newComment.comment,
                    parent: this.newComment.parent
                })
                .then(response => {
                    this.newComment = {
                        name: "",
                        comment: "",
                        parent: null
                    };
                    this.erros = [];
                    this.$toastr.s(`The comment was created.`);
                    this.getComments();
                })
                .catch(error => {
                    this.erros = error.response.data;
                });
        }
    },
    computed: {
        hasChildren() {
            const { children } = this.comment;
            return children && children.length > 0;
        }
    }
};
</script>
