/**
 * CanvasのCoreクラス
 * - canvas/context の管理
 * - クリア処理
 * - 座標変換
 * - 画像描画
 * - テキスト描画
 * - パス描画
 */
export class CanvasRenderer {
    // 画面のサイズ
    #canvas;
    #width;
    #height;
    #ctx;

    /**
     * コンストラクタ
     */
    constructor(id = 'canvas') {
        this.#init(id);
    }

    /**
     * 初期化メソッド
     * @param string CanvasのID
     */
    #init(id) {
        this.#canvas = document.getElementById(id);

        // Canvasの存在の確認
        if (!this.#canvas) {
            throw new Error(`Canvas element '${id}' が見つかりません`);
        }

        this.#width = this.#canvas.width;
        this.#height = this.#canvas.height;
        this.#ctx = this.#canvas.getContext('2d');
        this.#ctx.font = '50px Arial sans-serif';
        this.#ctx.fillStyle = "black";
        this.#ctx.textAlign = 'center';
        this.#ctx.textBaseline = 'middle';
    }

    /**
     * 
     * @param {*} width 
     * @param {*} height 
     */
    clear(width, height) {
        this.#ctx.clearRect(0, 0, this.#width, this.#height);
    }

    // 描画
    drawing(width, height) {

    }

    get width() {
        return this.#width;
    }

    get height() {
        return this.#height;
    }

    get canvas() {
        return this.#canvas;
    }

    get ctx() {
        return this.#ctx;
    }
}
