/**
 * CanvasのCoreクラス
 * - canvas/context の管理
 * - クリア処理
 * - 座標変換
 * - 画像描画
 * - テキスト描画
 * - パス描画
 */
export class InputManager {
    #x;
    #y;
    #isDown;

    /**
     * コンストラクタ
     */
    constructor(x = 0, y = 0) {
        this.#x = x;
        this.#y = y;
        this.#isDown = false;
    }

    update() {
        this.#isDown = false;
    }
}
