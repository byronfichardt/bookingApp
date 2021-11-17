<template>
    <div>
        <v-file-input
            v-model="file"
            show-size
            accept="image/*"
            label="Inspiration picture. (optional)"
            @change="handleUploadFile()"
        ></v-file-input>
        <progress max="100" :value.prop="uploadPercentage"></progress>
    </div>
</template>

<script>
import { bus } from "../app";
export default {
    data: () => ({
        file: null,
        uploadPercentage: 0
    }),
    methods: {
        handleUploadFile(){
            console.log(this.file);
            this.submitFile();
        },
        submitFile(){
            if(this.file === null) {
                return;
            }

            const config = {
                onUploadProgress: (progressEvent) => {
                    this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 75 ) );
                    bus.$emit('file-uploading')
                }
            }
            /*
                Initialize the form data
            */
            let formData = new FormData();

            /*
                Add the form data we need to submit
            */
            formData.append('file', this.file);

            /*
                Make the request to the POST /single-file URL
            */
            axios.post( 'api/bookings/file-upload',
                formData,
                config
            ).then((res) => {
                bus.$emit('file-uploaded', res.data)
                this.uploadPercentage = 100;
            }).catch(function () {
                console.log('FAILURE!!');
            });
        },
    }
}
</script>
