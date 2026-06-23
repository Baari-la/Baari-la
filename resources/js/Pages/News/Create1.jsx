import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import { useState, useEffect } from "react";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import axios from "axios";

export default function Create({ auth }) {
    const [lang, setLang] = useState("id");
    const [isTranslating, setIsTranslating] = useState(false);
    const [preview, setPreview] = useState(null);

    const { data, setData, post, processing } = useForm({
        title_id: "",
        title_en: "",
        content_id: "",
        content_en: "",
        slug: "",
        image: null,
    });

    // Handle Image Change & Preview
    const handleImageChange = (e) => {
        const file = e.target.files[0];
        setData("image", file);
        if (file) {
            setPreview(URL.createObjectURL(file));
        }
    };

    // 1. AUTO-TRANSLATE JUDUL & SLUG
    useEffect(() => {
        const timer = setTimeout(() => {
            if (data.title_id && data.title_id.length > 5) {
                setIsTranslating(true);
                axios
                    .post(route("news.translate"), { text: data.title_id })
                    .then((res) => {
                        // Gunakan fungsional update (prev) agar data lain (seperti image/content) tidak hilang
                        setData((prev) => ({
                            ...prev,
                            title_en: res.data.translated,
                            slug: res.data.slug,
                        }));
                    })
                    .catch((err) => console.error(err))
                    .finally(() => setIsTranslating(false));
            }
        }, 1500);
        return () => clearTimeout(timer);
    }, [data.title_id]);

    // 2. AUTO-TRANSLATE ISI BERITA (Content)
    useEffect(() => {
        const timer = setTimeout(() => {
            if (data.content_id && data.content_id.length > 50) {
                const plainText = data.content_id.replace(/<[^>]*>?/gm, "");
                axios
                    .post(route("news.translate"), { text: plainText })
                    .then((res) => {
                        setData((prev) => ({
                            ...prev,
                            content_en: `<p>${res.data.translated}</p>`,
                        }));
                    });
            }
        }, 3000);
        return () => clearTimeout(timer);
    }, [data.content_id]);

    const submit = (e) => {
        e.preventDefault();
        post(route("news.store"));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="font-black text-xl text-[#0a192f] uppercase tracking-tighter">
                    Bilingual Intelligence
                </h2>
            }
        >
            <Head title="Write News" />
            <div className="py-12 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* TAB SWITCHER */}
                    <div className="flex gap-2 bg-gray-200 p-1 rounded-xl w-fit mb-6">
                        <button
                            type="button"
                            onClick={() => setLang("id")}
                            className={`px-6 py-2 rounded-lg font-bold text-[10px] uppercase transition ${lang === "id" ? "bg-[#0a192f] text-yellow-500 shadow-md" : "text-gray-500"}`}
                        >
                            INDONESIA
                        </button>
                        <button
                            type="button"
                            onClick={() => setLang("en")}
                            className={`px-6 py-2 rounded-lg font-bold text-[10px] uppercase transition ${lang === "en" ? "bg-[#0a192f] text-yellow-500 shadow-md" : "text-gray-500"}`}
                        >
                            ENGLISH
                        </button>
                    </div>

                    <form
                        onSubmit={submit}
                        className="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100"
                    >
                        {/* UPLOAD GAMBAR */}
                        <div className="mb-8 p-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Intelligence Visual (Header Image)
                            </label>
                            <div className="flex items-center gap-6">
                                {preview ? (
                                    <img
                                        src={preview}
                                        className="w-32 h-32 object-cover rounded-2xl border-4 border-white shadow-lg"
                                        alt="Preview"
                                    />
                                ) : (
                                    <div className="w-32 h-32 bg-gray-200 rounded-2xl flex items-center justify-center text-[10px] text-gray-400 font-bold uppercase text-center p-4 italic">
                                        No Image Selected
                                    </div>
                                )}
                                <input
                                    type="file"
                                    onChange={handleImageChange}
                                    className="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#0a192f] file:text-yellow-500 hover:file:bg-navy-800 transition-colors"
                                />
                            </div>
                        </div>

                        {/* INPUT JUDUL */}
                        <div
                            className={lang === "id" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                {" "}
                                Judul (ID){" "}
                            </label>
                            <input
                                type="text"
                                value={data.title_id}
                                className="w-full p-4 rounded-xl border-gray-100 bg-gray-50 font-bold"
                                onChange={(e) =>
                                    setData("title_id", e.target.value)
                                }
                            />
                            <p className="mt-2 text-[9px] font-mono text-gray-400">
                                Slug: {data.slug}
                            </p>
                        </div>

                        <div
                            className={lang === "en" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Title (EN){" "}
                                {isTranslating && "- AI Translating..."}
                            </label>
                            <input
                                type="text"
                                value={data.title_en}
                                className="w-full p-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-blue-600"
                                onChange={(e) =>
                                    setData("title_en", e.target.value)
                                }
                            />
                        </div>

                        {/* EDITOR KONTEN */}
                        <div
                            className={lang === "id" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-[#0a192f] uppercase mb-2">
                                {" "}
                                Konten Indonesia{" "}
                            </label>
                            <CKEditor
                                editor={ClassicEditor}
                                data={data.content_id}
                                onChange={(event, editor) =>
                                    setData("content_id", editor.getData())
                                }
                            />
                        </div>

                        <div
                            className={lang === "en" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-blue-600 uppercase mb-2">
                                {" "}
                                English Content (Auto-Drafted){" "}
                            </label>
                            <CKEditor
                                editor={ClassicEditor}
                                data={data.content_en}
                                onChange={(event, editor) =>
                                    setData("content_en", editor.getData())
                                }
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0a192f] text-yellow-500 font-black py-4 rounded-2xl uppercase tracking-widest hover:bg-navy-800 transition disabled:opacity-50"
                        >
                            {processing
                                ? "Publishing..."
                                : "Publish Intelligence Report"}
                        </button>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
