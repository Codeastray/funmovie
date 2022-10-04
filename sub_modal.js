app.component('sub_modal', {
    template:
        // prevent 送出後頁面不會從新載入
        /*html*/
        `<div class="modal fade " id="myModal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="show_name"></h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <!-- modal中的內容，需要改的地方 -->
                    <div class="embed-responsive embed-responsive-21by9">
                        <!-- 到YouTube上在影片按滑鼠右鍵選複製崁入影片連結 得到以下程式碼-->
                        <iframe width="1280" height="720" id="myMovie"
                            src="https://www.youtube.com/embed/VNQedoh5XKE"
                            title="[電影預告] Marvel Studios《奇異博士2: 失控多元宇宙》宣傳片 - Prove It (中文字幕)" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button v-if="exist_or_not_moviename == 0" class="btn btn-success" @click="b">加入我的片單</button>
                    <button v-else :class="{'disabledButton':exist_or_not_moviename == 1}" :disabled="exist_or_not_moviename == 1">已加入我的片單</button>
                </div>
            </div>
        </div>
    </div>`,
    props: {
        exist_or_not_moviename: {
            type: Number,
            required: true
        }
    }
    ,
    methods: {
        b() {
            var jsonData = {};
            // 點擊後，將抓取當下該影片的片名後傳Ajax，新增我的片單
            console.log(moviename_title)
            jsonData["moviename"] = moviename_title;
            
            $.ajax({
                type: "POST",
                url: "subuser_save_C&D_api.php",
                data: JSON.stringify(jsonData),
                dataType: "json",
                success: showdata_save_C_D,
                error: function () {
                    alert("error-subuser_save_C&D_api.php");
                }
            });
            location.reload();
        }
    },
    

    
})
