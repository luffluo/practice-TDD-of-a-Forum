<script>
    import Replies from './Replies';

    export default {
        props: ['initialRepliesCount'],

        components: {Replies},

        data() {
            return {
                repliesCount: this.initialRepliesCount,
            };
        },
    };
</script>