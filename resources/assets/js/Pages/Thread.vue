<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscribeButton';

    export default {
        props: ['dataRepliesCount', 'dataLocked'],

        components: {Replies, SubscribeButton},

        data() {
            return {
                repliesCount:this.dataRepliesCount,
                locked:this.dataLocked //接收属性
            };
        },
    };
</script>