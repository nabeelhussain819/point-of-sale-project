<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <a-button v-on:click="addNewPost" type="primary">
                            Primary
                        </a-button>
                     asd   I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            //exmaple
            addNewPost() {
                axios.post('/example', {title: "title", user_id: "title"})
                    .then((response) => {

                        $('#success').html(response.data.message)

                    })
            }
        }
    }
</script>
