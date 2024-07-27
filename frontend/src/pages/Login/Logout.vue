<script scoped>

export default {
    name: "Logout",
    async created() {
        
        const vok = await this.$auth.logout(this);
        if(vok){
            this.$router.replace('/login');
        }
        console.log(vok);
        console.log(this.$auth.getId);         
    },
}
</script>
