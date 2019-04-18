<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterTopQuestionsC2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_questions_c2', function (Blueprint $table) {
            $table->increments('qst_newsletter_id');
                        
            # date from link to top questions from parliament website
            # https://www.hellenicparliament.gr/Koinovouleftikos-Elenchos/Deltio-Epikairon-Erotiseon?search=on
            $table->date('qst_newsletter_date');

            # ex. "222/1992-01-30"
            $table->string('qst_id');

            # Numbering at the site of the newsletter (1,2,3...)
            $table->integer('qst_numbering');
            
            # The date the question first asked 1992-01-30
            $table->date('qst_addressed_date');

            $table->text('qst_title');
            $table->text('person_info');
            $table->string('adl_id');
            $table->string('type');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('top_questions_c2');
    }
}
