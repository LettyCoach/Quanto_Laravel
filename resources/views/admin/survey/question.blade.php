<!-- Modal -->
<div class="modal fade" id="modalAddQuestion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">質問追加</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-form-label">質問タイプと回答を選んでください:</label>
                </div>
                <div class="dropQuestion">
                    <div class="dropArea" ondrop="dropQuestion(event)" ondragover="allowDrop(event)" id="addQuestionDropArea"></div>
                    <div class="dragArea">
                        <div>質問を選択ください</div>
                        <div class="questionType">

                            @foreach ($questionTypes as $q)
                                <div id="questionType-{{$q->id}}" draggable="true" ondragstart="dragElement(event, DRAG_TYPE.QUESTION, {{$q->id}})">{{$q->name}}</div>
                            @endforeach

                            <div class="questionRequired" id="questionRequired">必須</div>
                            <div class="questionRequired" id="questionNonRequired">任意</div>
                        </div>
                        <div>回答を選択ください</div>
                        <div class="answerType">

                            @foreach ($answerTypes as $a)
                                @if ($a->name == "チェックボックス")
                                    <div style="align-items: center; display: flex;">
                                        <div id="questionType-{{$a->id}}" draggable="true" ondragstart="dragElement(event, DRAG_TYPE.ANSWER, {{$a}})">{{$a->name}}</div>
                                        <input id="answerLimit" type="number" min="0" placeholder="0は無制限" style="margin-left: 5px; width: 7em;"/>
                                    </div>
                                @else
                                    <div id="questionType-{{$a->id}}" draggable="true" ondragstart="dragElement(event, DRAG_TYPE.ANSWER, {{$a}})">{{$a->name}}</div>
                                @endif
                            @endforeach
                        </div>
                        <hr style="border-color: black;">
                        <div>オプション</div>
                        <div class="answerOption">

                            @foreach ($answerOptions as $a)
                                <div id="questionType-{{$a->id}}" draggable="true" ondragstart="dragElement(event, DRAG_TYPE.OPTION, {{$a}})">{{$a->name}}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 15px; width: 100px; height: 30px; font-size: 12px; font-weight: bold; color: grey; background: #e3e3e3;border: 0;">キャンセル</button>
                <button type="button" class="btn btn-primary" id="btnAddQuestion" style="background: rgb(227, 113, 203); border-radius: 15px; width: 80px; height: 30px; font-weight: bold;font-size: 12px; border: 0;">保存</button>
            </div>
            <input type="hidden" id="container-id">
        </div>
    </div>
    <div class="edit-buttons-layout">
        <div class="d-flex" style="display:none;justify-content:center;">
            <img id="edit-buttons-save-off" style="display:none; width: 100px;" src="{{ asset('public/img/save_off.png') }}" onclick="saveQuestion()">
            <img id="edit-buttons-save-on" style="display:none; width: 100px; display: none; " src="{{ asset('public/img/save_on.png') }}" onclick="saveQuestion()">
        </div>
        <div class="d-flex" style="justify-content:center;">
            <div id="edit-buttons-spinner" style="display: none;" class="loadingio-spinner-ellipsis-tf43i957w4"><div class="ldio-fweak2gcswn">
            <div></div><div></div><div></div><div></div><div></div>
            </div></div>
        </div>
        <div id="edit-buttons-time" class="d-flex" style="justify-content:center; display: none; color: white; z-index: 1000">
            
        </div>
    </div>
</div>
<script>
var save_url = "{{url('admin/survey/save')}}";
</script>
<style>
.edit-buttons-layout {
    position: fixed;
    top: 15%;
    right: 40%;
    width: 300px;
    height: 40px;
    z-index: 1000;
}
@keyframes ldio-fweak2gcswn {
   0% { transform: translate(12px,80px) scale(0); }
  25% { transform: translate(12px,80px) scale(0); }
  50% { transform: translate(12px,80px) scale(1); }
  75% { transform: translate(80px,80px) scale(1); }
 100% { transform: translate(148px,80px) scale(1); }
}
@keyframes ldio-fweak2gcswn-r {
   0% { transform: translate(148px,80px) scale(1): }
 100% { transform: translate(148px,80px) scale(0); }
}
@keyframes ldio-fweak2gcswn-c {
   0% { background: #00eeff }
  25% { background: #abfd2e }
  50% { background: #ff7ae9 }
  75% { background: #ffed00 }
 100% { background: #00eeff }
}
.ldio-fweak2gcswn div {
  position: absolute;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  transform: translate(80px,80px) scale(1);
  background: #00eeff;
  animation: ldio-fweak2gcswn 1.4925373134328357s infinite cubic-bezier(0,0.5,0.5,1);
}
.ldio-fweak2gcswn div:nth-child(1) {
  background: #ffed00;
  transform: translate(148px,80px) scale(1);
  animation: ldio-fweak2gcswn-r 0.3731343283582089s infinite cubic-bezier(0,0.5,0.5,1), ldio-fweak2gcswn-c 1.4925373134328357s infinite step-start;
}.ldio-fweak2gcswn div:nth-child(2) {
  animation-delay: -0.3731343283582089s;
  background: #00eeff;
}.ldio-fweak2gcswn div:nth-child(3) {
  animation-delay: -0.7462686567164178s;
  background: #ffed00;
}.ldio-fweak2gcswn div:nth-child(4) {
  animation-delay: -1.1194029850746268s;
  background: #ff7ae9;
}.ldio-fweak2gcswn div:nth-child(5) {
  animation-delay: -1.4925373134328357s;
  background: #abfd2e;
}
.loadingio-spinner-ellipsis-tf43i957w4 {
  width: 200px;
  height: 200px;
  display: inline-block;
  overflow: hidden;
  background: rgba(NaN, NaN, NaN, 0);
}
.ldio-fweak2gcswn {
  width: 100%;
  height: 100%;
  position: relative;
  transform: translateZ(0) scale(1);
  backface-visibility: hidden;
  transform-origin: 0 0; /* see note above */
}
.ldio-fweak2gcswn div { box-sizing: content-box; }
</style>


